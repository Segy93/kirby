<?php

namespace App\Http\Controllers;

use App\Components\AdminArticles;
use App\Components\AdminArticlesEdit;
use App\Components\AdminBanners;
use App\Components\AdminCategories;
use App\Components\AdminCheckoutTable;
use App\Components\AdminComments;
use App\Components\AdminErrorModal;
use App\Components\AdminHeader;
use App\Components\AdminHeading;
use App\Components\Administrators;
use App\Components\AdminLogin;
use App\Components\AdminOrder;
use App\Components\AdminOrders;
use App\Components\AdminRoles;
use App\Components\AdminSEO;
use App\Components\AdminSidebar;
use App\Components\AdminStaticCategories;
use App\Components\AdminStaticPage;
use App\Components\AdminStaticPages;
use App\Components\AdminTags;
use App\Components\AdminUserAddresses;
use App\Components\AdminUsers;
use App\Components\Header;
use App\Components\HeaderCompanyInfo;
use App\Components\HeaderLogo;
use App\Exceptions\PermissionException;
use App\Providers\AdminService;
use Illuminate\Http\Request;

class AdminController extends BaseController {

    protected $css = [
        'libs/bootstrap/css/bootstrap.min.css',
        'libs/bootstrap/css/bootstrap-theme.min.css',
        'libs/admin_common_landings.css',
    ];

    protected $js = [
        'libs/plugins/jQuery/jquery-2.2.3.min.js',
        'libs/bootstrap/js/bootstrap.min.js',


        // Dodatne biblioteke
        'libs/Sortable.min.js',

        'libs/MonitorMainAjax.js',
        'libs/MonitorMainDOM.js',
        'libs/MonitorMainEditor.js',
        'libs/MonitorMainShortcodes.js',
    ];




    public function loginPage() {
        $this->content['main']    [] = new AdminLogin();
    }

    protected function pageHeader():void {
    }

    protected function pageMenu(string $page):void {
    }

    public function administrationPage() {
        $page = 'administration';
        $this->content['header']  [] = new AdminHeader();
        $this->content['sidebar'] [] = new AdminSidebar($page);
        $this->content['main']    [] = new AdminErrorModal();
    }

    public function permissionsPage() {
        $this->content['main']    [] = new AdminHeading('Dozvole');
        $this->content['header']  [] = new AdminHeader();
        $this->content['main']    [] = new AdminRoles();
        $this->content['sidebar'] [] = new AdminSidebar('dozvole');
        $this->content['main']    [] = new AdminErrorModal();
    }

    public function administratorsPage() {
        $this->content['main']    [] = new AdminHeading('Administratori');
        $this->content['header']  [] = new AdminHeader();
        $this->content['main']    [] = new Administrators();
        $this->content['sidebar'] [] = new AdminSidebar('administratori');
        $this->content['main']    [] = new AdminErrorModal();
    }

    public function usersPage() {
        $this->content['main']    [] = new AdminHeading('Korisnici');
        $this->content['header']  [] = new AdminHeader();
        $this->content['main']    [] = new AdminUsers();
        $this->content['sidebar'] [] = new AdminSidebar('korisnici');
        $this->content['main']    [] = new AdminErrorModal();
    }

    public function usersAddressesPage($params = null) {
        $user_id = intval($params);
        $this->content['main']    [] = new AdminHeading('Adrese korisnika');
        $this->content['header']  [] = new AdminHeader();
        $this->content['main']    [] = new AdminUserAddresses($user_id);
        $this->content['sidebar'] [] = new AdminSidebar('Adrese korisnika');
        $this->content['main']    [] = new AdminErrorModal();
    }


    public function adminOrdersPage($params = [], $additional = [], $url = [], $full_url = '') {
        $this->content['main']    [] = new AdminHeading('Narudžbine');
        $this->content['header']  [] = new AdminHeader();
        $this->content['main']    [] = new AdminOrders($url);
        $this->content['sidebar'] [] = new AdminSidebar('narudzbine');
        $this->content['main']    [] = new AdminErrorModal();
    }

    public function adminOrderPage($params = null) {
        $order_id = intval($params);
        $logo_print_only = true;
        $this->content['main']    [] = new AdminHeading('Narudžbina');
        $this->content['header']  [] = new AdminHeader();
        $this->content['header']  [] = new Header(new HeaderLogo($logo_print_only), new HeaderCompanyInfo());
        $this->content['main']    [] = new AdminOrder($order_id);
        $this->content['sidebar'] [] = new AdminSidebar('narudzbine');
        $this->content['main']    [] = new AdminErrorModal();
    }

    public function adminCheckoutTablePage($params = null) {
        $order_id = intval($params);
        $logo_print_only = true;
        $this->content['main']    [] = new AdminHeading('Narudžbenica ' . $order_id);
        $this->content['header']  [] = new AdminHeader();
        $this->content['header']  [] = new Header(new HeaderLogo($logo_print_only), new HeaderCompanyInfo());
        $this->content['main']    [] = new AdminCheckoutTable($order_id);
        $this->content['sidebar'] [] = new AdminSidebar('narudzbine');
        $this->content['main']    [] = new AdminErrorModal();
    }

    public function adminBannerPage($params = null) {
        $order_id = intval($params);
        $this->content['main']    [] = new AdminHeading('Baneri');
        $this->content['header']  [] = new AdminHeader();
        $this->content['main']    [] = new AdminBanners();
        $this->content['sidebar'] [] = new AdminSidebar('baneri');
        $this->content['main']    [] = new AdminErrorModal();
    }


    public function adminCommentPage($params = null) {
        $order_id = intval($params);
        $this->content['main']    [] = new AdminHeading('Komentari');
        $this->content['header']  [] = new AdminHeader();
        $this->content['main']    [] = new AdminComments();
        $this->content['sidebar'] [] = new AdminSidebar('komentari');
        $this->content['main']    [] = new AdminErrorModal();
    }

    public function articlesPage() {
        $this->content['main']      [] = new AdminHeading('Članci');
        $this->content['main']      [] = new AdminSEO('article');
        $this->content['header']    [] = new AdminHeader();
        $this->content['main']      [] = new AdminArticles();
        $this->content['sidebar']   [] = new AdminSidebar('clanci');
        $this->content['main']      [] = new AdminErrorModal();
        //var_dump($this->content['main']);die;
    }

    public function articlesEditPage($params = null) {
        $this->content['main']      [] = new AdminHeading('Članci');
        $this->content['header']    [] = new AdminHeader();
        $this->content['main']      [] = new AdminArticlesEdit($params);
        $this->content['sidebar']   [] = new AdminSidebar('clanci');
        $this->content['main']      [] = new AdminErrorModal();
    }

    public function tagsPage() {
        $this->content['main']      [] = new AdminHeading('Tagovi');
        $this->content['main']      [] = new AdminSEO('tag');
        $this->content['header']    [] = new AdminHeader();
        $this->content['main']      [] = new AdminTags();
        $this->content['sidebar']   [] = new AdminSidebar('tagovi');
    }

    public function categoriesPage() {
        $this->content['main']      [] = new AdminHeading('Kategorije');
        $this->content['main']      [] = new AdminSEO('articleCategory');
        $this->content['header']    [] = new AdminHeader();
        $this->content['main']      [] = new AdminCategories();
        $this->content['sidebar']   [] = new AdminSidebar('kategorije');
    }

    public function staticCategoriesPage() {
        $this->content['main']      [] = new AdminHeading('Staticne kategorije');
        $this->content['main']      [] = new AdminSEO('category_static');
        $this->content['header']    [] = new AdminHeader();
        $this->content['main']      [] = new AdminStaticCategories();
        $this->content['sidebar']   [] = new AdminSidebar('staticne/kategorije');
    }

    public function staticPagesPage() {
        $this->content['main']      [] = new AdminHeading('Staticne strane');
        $this->content['main']      [] = new AdminSEO('static_pages', 'update');
        $this->content['header']    [] = new AdminHeader();
        $this->content['main']      [] = new AdminStaticPages();
        $this->content['sidebar']   [] = new AdminSidebar('staticne/strane');
    }

    public function staticPagePage($params = null) {
        $this->content['main']      [] = new AdminHeading('Staticne strane');
        if ($params === null) {
            $this->content['main']      [] = new AdminSEO('static_pages');
        }
        $this->content['header']    [] = new AdminHeader();
        $this->content['main']      [] = new AdminStaticPage($params);
        $this->content['sidebar']   [] = new AdminSidebar('staticne/strane');
    }


    protected function pageFooter():void {
    }









    //Admin Dashboard
    public function administration(Request $request) {
        if (AdminService::isAdminLoggedIn()) {
            return $this->getView('administration');
        } else {
            return redirect()->route('LogIn');
        }
    }

    public function administrators(Request $request) {
        if (AdminService::isAdminLoggedIn()) {
            return $this->getView('administrators');
        } else {
            return redirect()->route('LogIn');
        }
    }

    public function users(Request $request) {
        if (AdminService::isAdminLoggedIn()) {
            return $this->getView('users');
        } else {
            return redirect()->route('LogIn');
        }
    }

    public function usersAddresses(Request $request, $user_id) {
        if (AdminService::isAdminLoggedIn()) {
            return $this->getView('usersAddresses', $user_id);
        } else {
            return redirect()->route('LogIn');
        }
    }

    public function checkpassword(Request $request) {
        $username = $request->input('username');
        $password = $request->input('password');

        $_SESSION['admin_controller']['failed_attempts'] = 0;

        try {
            AdminService::login($username, $password);
            $_SESSION['admin_controller']['failed_attempts'] = 0;
            $redirect_to = 'Admin';
        } catch (PermissionException $e) {
            $_SESSION['admin_controller']['failed_attempts'] = array_key_exists(
                'failed_attempts',
                $_SESSION['admin_controller']
            )
                ? $_SESSION['admin_controller']['failed_attempts'] + 1
                : 1
            ;

            $redirect_to = 'LogIn';
            sleep($_SESSION['admin_controller']['failed_attempts'] * (rand(1, 3) * 0.1));
        }

        return redirect()->route($redirect_to);
    }

    public function permissions(Request $request) {
        if (AdminService::isAdminLoggedIn()) {
            return $this->getView('permissions');
        } else {
            return redirect()->route('LogIn');
        }
    }

    //Admin Login Strana
    public function login(Request $request) {
        return $this->getView('login');
    }

    public function logout() {
        AdminService::logout();
        return redirect()->route('LogIn');
    }

    public function roles(Request $request) {
        if (AdminService::isAdminLoggedIn()) {
            return $this->getView('roles');
        } else {
            return redirect()->route('LogIn');
        }
    }

    public function orders(Request $request) {
        if (AdminService::isAdminLoggedIn()) {
            return $this->getView('adminOrders', [], [], [$request->all()]);
        } else {
            return redirect()->route('LogIn');
        }
    }

    public function order(Request $request, $order_id) {
        if (AdminService::isAdminLoggedIn()) {
            return $this->getView('adminOrder', $order_id);
        } else {
            return redirect()->route('LogIn');
        }
    }

    public function checkoutTable(Request $request, $user_id) {
        if (AdminService::isAdminLoggedIn()) {
            return $this->getView('adminCheckoutTable', $user_id);
        } else {
            return redirect()->route('LogIn');
        }
    }

    public function banner() {
        if (AdminService::isAdminLoggedIn()) {
            return $this->getView('adminBanner');
        } else {
            return redirect()->route('LogIn');
        }
    }

    public function comment() {
        if (AdminService::isAdminLoggedIn()) {
            return $this->getView('adminComment');
        } else {
            return redirect()->route('LogIn');
        }
    }

    public function articles() {
        if (AdminService::isAdminLoggedIn()) {
            return $this->getView('articles');
        } else {
            return redirect()->route('LogIn');
        }
    }
    public function articlesEdit(Request $request, $article_id) {
        if (AdminService::isAdminLoggedIn()) {
            return $this->getView('articlesEdit', $article_id);
        } else {
            return redirect()->route('LogIn');
        }
    }
    public function tags() {
        if (AdminService::isAdminLoggedIn()) {
            return $this->getView('tags');
        } else {
            return redirect()->route('LogIn');
        }
    }
    public function categories() {
        if (AdminService::isAdminLoggedIn()) {
            return $this->getView('categories');
        } else {
            return redirect()->route('LogIn');
        }
    }

    public function staticCategories() {
        if (AdminService::isAdminLoggedIn()) {
            return $this->getView('staticCategories');
        } else {
            return redirect()->route('LogIn');
        }
    }

    public function staticPages() {
        if (AdminService::isAdminLoggedIn()) {
            return $this->getView('staticPages');
        } else {
            return redirect()->route('LogIn');
        }
    }

    public function staticPage(Request $request, $page_id = null) {
        if (AdminService::isAdminLoggedIn()) {
            return $this->getView('staticPage', $page_id);
        } else {
            return redirect()->route('LogIn');
        }
    }
}
