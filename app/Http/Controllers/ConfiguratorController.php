<?php

namespace App\Http\Controllers;

use App\Components\AtomProductSingleCompact;
use App\Components\Banner;
use App\Components\Breadcrumbs;
use App\Components\Columns;
use App\Components\ConfigurationList;
use App\Components\ConfiguratorSetup;
use App\Components\MainContainer;
use App\Components\ProductCategory;
use App\Components\ProductFilter;
use App\Components\ProductSingle;
use App\Components\SettingsFilters;
use App\Providers\ConfigurationService;
use App\Providers\ConfiguratorService;
use App\Providers\SEOService;
use App\Providers\SessionService;
use App\Providers\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Kontroler za konfigurator
 */
class ConfiguratorController extends HomeController {


    /**
     * Ukljucuje sve potrebne komponente za prikaz stranice novog konfiguratora
     *
     * @param  string $configuration_name       Naziv konfiguracije
     * @param  string $full_url                 Puna url adresa
     *
     * @return void
     */
    public function newConfiguratorPage(?string $configuration_name = null, string $full_url) {
        $this->content['main'] [] = new MainContainer([
            [
                new Banner('Pozadina'),
            ],
            [
                new Breadcrumbs(),
                new ConfiguratorSetup($configuration_name),
                new Banner('Početna strana baner', null, 1),
            ],
        ]);
    }

    /**
     * Ukljucuje sve potrebne komponente za prikaz stranice liste proizvoda
     * @param array     $params                         Parametri
     *  param int       $params['category_id']          Id kategorije
     *  param string    $params['configuration_name']   Naziv konfiguracije
     * @param array     $additional                     Dodatno
     * @param array     $url                            Url adresa
     * @param string    $full_url                       Puna url adresa
     * @return void
     */
    public function configuratorProductListPage(array $params = [], $additional = [], $url = [], $full_url = '') {
        $is_configurator    = true;
        $show_link          = true;
        $category_id        = intval($params['category_id']);
        $configuration_name = $params['configuration_name'];
        $product_single     = new ProductSingle($show_link, $is_configurator, $configuration_name);
        $product_compact    = new AtomProductSingleCompact();
        $url_filter         = [];
        $banner_category    = new Banner('Svaki peti u listi', $full_url, 'product_category');
        if (is_array($url)) {
            foreach ($url as $filter_name => $filter_value) {
                $url[$filter_name] = explode(',', $filter_value);
                foreach ($url[$filter_name] as $key => $value) {
                    $new_value = str_replace('commastring', ', ', $value);
                    $url[$filter_name][$key] = $new_value;
                    if (is_numeric($value)) {
                        $url[$filter_name][$key] = intval($new_value);
                    }
                }
            }
            $url_filter = $url;
        }
        $on_sale = strpos($full_url, 'akcija') !== false;
        $this->content['main'] [] = new MainContainer([
            [
                new Banner('Pozadina', $full_url, 'product_category'),
            ],
            [
                new Breadcrumbs(),
                new SettingsFilters(),
                new Columns(1, [
                    [
                        new ProductFilter($url_filter, $category_id, $on_sale, $is_configurator, $configuration_name)
                    ],
                    [
                        new ProductCategory(
                            $product_single,
                            $product_compact,
                            $category_id,
                            $url_filter,
                            $additional,
                            $banner_category,
                            $full_url
                        ),
                    ],
                ]),
                new Banner('Početna strana baner', null, 1),
            ],
        ]);
    }

    /**
     * Stranica liste konfiguratora
     *
     * @param string    $full_url        Puna url adresa
     *
     * @return void
     */
    public function configurationListPage(string $full_url): void {
        $this->content['main'] [] = new MainContainer([
            [
                new Banner('Pozadina'),
            ],
            [
                new Breadcrumbs(),
                new ConfigurationList(),
                new Banner('Početna strana baner', null, 1),
            ],
        ]);
    }

    /**
     * Poziva metode za dodavanje proizvoda u konfiguracionu listu korisnika
     *
     * @param Request $request
     * @return void
     */
    public function addToConfigurator(Request $request) {
        $product_id = intval($request->input('product_id'));
        $configuration_id = $request->input('configuration_id') !== ''
            ? intval($request->input('configuration_id'))
            : null
        ;

        $products_array = ConfigurationService::getProductConfigurationArray($configuration_id);
        if (!ConfiguratorService::isCompatible($product_id, $products_array)) {
            $configuration_key = $configuration_id === null ? 0 : $configuration_id;
            $session_key = 'configuration_error_' . $configuration_key;
            $is_array = true;
            $error = [
                'product_id' => $product_id,
                'message'    => 'Proizvod nije kompatibilan sa konfiguracijom!',
            ];
            SessionService::setSessionForService($session_key, $error, $is_array, 'ConfigurationService');
        }
        if ($configuration_id !== null) {
            $configuration = ConfigurationService::getConfigurationById($configuration_id);
            $user = UserService::getCurrentUser();
        }
        ConfigurationService::changeConfigurationProduct($product_id, $configuration_id);
        return $configuration_id === null
            ? redirect()->route('configurator')
            : redirect()->route('configurator', [
                'username'  => $user->username,
                'name'      => urlencode($configuration->name)
            ])
        ;
    }

    /**
     * Unosi konfigurator u bazu
     *
     * @param Request $request
     * @return void
     */
    public function configuratorCreate(Request $request) {
        $name = $request->input('name');
        if (UserService::isUserLoggedIn()) {
            $user = UserService::getCurrentUser();
            if (ConfigurationService::isConfigurationNameTaken($name)) {
                return redirect()->route('configurator');
            }
            $configuration = ConfigurationService::placeConfiguration(UserService::getCurrentUserId(), $name);
            ConfigurationService::deleteConfiguration();
            return redirect()->route('configurator', [
                'username'  => urlencode($user->username),
                'name'      => urlencode($configuration->name)
            ]);
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Izmena konfiguratora
     *
     * @param Request $request
     * @return void
     */
    public function configuratorEdit(Request $request) {
        $configuration_id = intval($request->input('configuration_id'));
        $name = $request->input('name');
        if (UserService::isUserLoggedIn()) {
            $user = UserService::getCurrentUser();
            if (ConfigurationService::isConfigurationNameTaken($name, $configuration_id)) {
                $configuration = ConfigurationService::getConfigurationById($configuration_id);
                return redirect()->route('configurator', [
                    'username'  => urlencode($user->username),
                    'name'      => urlencode($configuration->name),
                ]);
            }
            ConfigurationService::updateConfiguration($configuration_id, ['name' => $name]);
            return redirect()->route('configurator', [
                'username'  => urlencode($user->username),
                'name'      => urlencode($name)
            ]);
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Brisanje konfiguracije
     *
     * @param   Request  $request
     *
     * @return  RedirectResponse        Redirekcija na listu konfiguracija
     */
    public function configurationDelete(Request $request): RedirectResponse {
        $configuration_id = intval($request->input('configuration_id'));
        ConfigurationService::deleteConfiguration($configuration_id);

        return redirect()->route('configurationList');
    }

    /**
     * Brisanje pojedinacnog proizvoda
     *
     * @param   Request $request
     *
     * @return  RedirectResponse        Redirekcija na konfigurator
     */
    public function configuratorItemDelete(Request $request): RedirectResponse {
        $name = $request->input('name') !== '' ? $request->input('name') : null;
        $product_id = intval($request->input('product_id'));
        $configuration_id = null;
        $user = UserService::getCurrentUser();
        if ($name !== null) {
            $configuration_id = ConfigurationService::getConfigurationIdByName($name);
        }
        ConfigurationService::deleteConfigurationProduct($product_id, $configuration_id);
        return $configuration_id === null
            ? redirect()->route('configurator')
            : redirect()->route('configurator', [
                'username'  => urlencode($user->username),
                'name'      => urlencode($name)
            ])
        ;
    }

    /**
     * Lista konfiguracija
     *
     * @param Request $request
     * @return mixed            RedirectResponse | View
     */
    public function configurationList(Request $request) {
        if (UserService::isUserLoggedIn()) {
            return $this->getView('configurationList', urldecode($request->fullUrl()));
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Dobijen zahtev za stranicu novog konfiguratora
     *
     * @return
     */
    public function newConfigurator(Request $request, ?string $name = null) {
        if ($name !== null) {
            $name = urldecode($name);
        }
        if (UserService::isUserLoggedIn() || $name === null)
            return $this->getView(
                'newConfigurator',
                $name,
                urldecode($request->fullUrl())
            );
        else {
            return redirect()->route('login');
        }
    }

    /**
     * Zahtev za prikaz strane liste proizvoda
     *
     * @param Request   $request
     * @param string    $category_url   Url kategorije
     * @param string    $name           Naziv konfiguratora
     * @return
     */
    public function configuratorProductList(
        Request $request,
        string $category_url,
        string $name = 'trenutni'
    ) {
        $name = urldecode($name);
        $seo = SEOService::getSEOByURL($category_url);
        $info = explode('_', $seo->machine_name);
        $category_id = intval($info[1]);
        return $this->getView(
            'configuratorProductList',
            [
                'category_id'        => $category_id,
                'configuration_name' => $name,
            ],
            [],
            $request->all(),
            urldecode($request->fullUrl())
        );
    }
}

