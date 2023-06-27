<?php
    $protocol = 'http' . (!empty($_SERVER['HTTPS']) ? 's' : '') . '://';
    $base_url = $protocol . $_SERVER['SERVER_NAME'];
?>
<!DOCTYPE html>
<html lang = "sr">
    <head >
        <title>{{ !empty($seo->title) ? $seo->title : 'Kese za Kirby | eXelence d.o.o | Banovo brdo, Čukarica' }}</title>

        <base href="{{ (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST']}}">
        <link href="apple-touch-icon-72x72.png" rel="apple-touch-icon" sizes="72x72"/>
        <link href="apple-touch-icon-144x144.png" rel="apple-touch-icon" sizes="144x144"/>
        <link href="apple-touch-icon-60x60.png" rel="apple-touch-icon" sizes="60x60"/>
        <link href="apple-touch-icon-114x114.png" rel="apple-touch-icon" sizes="114x114"/>
        <link href="apple-touch-icon-57x57.png" rel="apple-touch-icon" sizes="57x57"/>
        <meta name="msapplication-TileImage" content="mstile-144x144.png"/>
        <meta name="application-name" itemprop="name" content="Kirby">
        <meta name="apple-mobile-web-app-title" content="Kirby">
        @if ($is_dev)
            <meta name="robots" content="noindex">
        @endif
        <link href="apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120"/>
        <link href="apple-touch-icon-76x76.png" rel="apple-touch-icon" sizes="76x76"/>
        <link href="favicon-32x32.png" rel="icon" sizes="32x32" type="image/png" type = "image/x-icon"/>
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="icon" type="image/png" sizes="512x512" href="/favicon-512x512.png">
        <link rel="icon" type="image/png" sizes="1024x1024" itemprop="image" href="/favicon-1024x1024.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#0056a9">
        <meta name="msapplication-TileColor" content="#0056a9">
        <meta name="msapplication-TileImage" content="/mstile-144x144.png">
        <meta name="theme-color" content="#0056a9">
        <meta itemprop="name" content="{{ !empty($seo) ? $seo->title : 'Kese za Kirby' }}">
        <meta name= "description" content="{{ !empty($seo->description) ? $seo->description : 'Kese za Kirby | eXelence d.o.o | Banovo brdo, Čukarica' }}">
        <meta name= "keywords" content="{{ !empty($seo->keywords) ? $seo->keywords : 'Kese za Kirby, eXelence d.o.o, dodatna oprema, rezervni delovi, Banovo brdo, Čukarica, jeftino, povoljno' }}">

        @if (!empty($seo))
            @if (!empty($seo->image_open_graph))
                <meta itemprop="image" content="{{$base_url}}/{{ $seo->image_open_graph }}">
            @endif


            <meta name="twitter:card" content="summary">
            <meta name="twitter:site" content="{{ $seo->twitter_handle_publisher }}">
            <meta name="twitter:title" content="{{ $seo->title }}">
            <meta name="twitter:description" content="{{ $seo->description }}">
            <meta name="twitter:creator" content="{{ $seo->twitter_handle_author }}">
            <meta name="twitter:image" content="{{$base_url}}/{{ $seo->image_twitter }}">
            @if (!empty($seo->image_twitter))
                <meta name="twitter:image" content="{{$base_url}}/{{ $seo->image_twitter }}">
            @else
                <meta
                    property="twitter:image"
                    content="{{ $base_url }}/android-chrome-256x256.png"
                />
            @endif

            <meta property="og:title" content="{{ $seo->title }}" />
            <meta property="og:type" content="article" />
            @if (!empty($seo->image_open_graph))
                <meta
                    property="og:image"
                    content="{{ $base_url }}/{{ $seo->image_open_graph }}"
                />
            @else
                <meta
                    property="og:image"
                    content="{{ $base_url }}/android-chrome-256x256.png"
                />
            @endif
            <meta property="og:description" content="{{ $seo->description }}" />
            <meta property="og:url" content="{{ $base_url }}/{{ $seo->url !== 'pocetna' ? $seo->url : '' }}" />
            <meta property="og:site_name" content="Kirby" />
        @else
            <meta name= "description" content="Kese za Kirby | eXelence d.o.o | Banovo brdo, Čukarica">
            <meta name= "keywords" content="Kese za Kirby, eXelence d.o.o, dodatna oprema, rezervni delovi, Banovo brdo, Čukarica, jeftino, povoljno">
        @endif
        <meta name="format-detection" content="telephone=no">


        <?php
            // ovo je ovde trenutno zato sto ne mogu lumenovim builtin funkcijama da ga skinem
            // tako da od opcija ostaje ovako nesto u sirovom php-u ili u php.ini da ga iskljucimo
            // ako imas ideju gde da ga stavim bolje reci
            header_remove('x-powered-by');
        ?>


        <meta itemprop = "paymentAccepted" content = "Cash, Credit Card"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {!!$csrf_meta!!}

        <?php $paths = []; ?>
        <?php $paths_external = []; ?>

        <?php // Ukljucivanje lokalnih css file-ova ?>

        @foreach ($css_local as $fajl)
            <?php $paths []= $fajl; ?>
        @endforeach

        @foreach ($content as $section)
            @foreach ($section as $component)
                <?php $css = $component->getCSS(); ?>
                @foreach ($css as $file)
                    <?php $paths []= $file; ?>
                @endforeach
                <?php $css_external = $component->getCSSExternal(); ?>
                @foreach ($css_external as $file)
                    <?php $paths_external []= $file; ?>
                @endforeach
            @endforeach
        @endforeach



        <?php $paths = array_unique($paths); ?>

        @if (MINIFIER_DISABLE)
            @foreach($paths as $path)
                <link rel="stylesheet" nonce = "{{$_SESSION['token']}}" href="/Components/{{ $path }}" />
            @endforeach
        @else
            <link rel="stylesheet" type="text/css" nonce = "{{$_SESSION['token']}}"  href="/min/?b=Components&f={{ implode($paths, ',') }}">
        @endif



        @foreach($paths_external as $path)
            <link nonce = "{{$_SESSION['token']}}" rel="stylesheet" href="{{ $path }}" />
        @endforeach
        <link nonce = "{{$_SESSION['token']}}" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700" rel="stylesheet">
    </head>
    <body>
        <svg
            class = "common_landings__display_none"
            height="24"
            version="1.1"
            viewBox="0 0 24 24"
            width="24"
            xmlns="http://www.w3.org/2000/svg"
        >
            <defs>
            @foreach ($content as $section)
                @foreach ($section as $component)
                    <?php $icons = $component->getIcons(); ?>
                    @foreach ($icons as $icon)
                        {!! view($icon) !!}
                    @endforeach
                @endforeach
            @endforeach
            </defs>
        </svg>

        <header>
            @foreach ($content['header'] as $component)
                {!! $component->renderHTML() !!}
            @endforeach
        </header>

        @if(!empty($content['sidebar']))
        <aside>
            @foreach ($content['sidebar'] as $component)
                {!! $component->renderHTML() !!}
            @endforeach
        </aside>
        @endif
        <nav>
            @foreach ($content['navigation'] as $component)
                {!! $component->renderHTML() !!}
            @endforeach
        </nav>

        <main class="wrapper">
                <div class="page-wrapper">
                    <div class="container-fluid">
                        @foreach ($content['main'] as $component)
                            {!! $component->renderHTML() !!}
                        @endforeach
                    </div>
                    <!-- /.container-fluid -->
                </div>
                <!-- /#page-wrapper -->
        </main>

        <footer class="footer">
            @foreach ($content['footer'] as $component)
                {!! $component->renderHTML() !!}
            @endforeach
        </footer>
        <script id = "test" nonce = "{{$_SESSION['token']}}" type="text/javascript" >
            if (window.Kirby === undefined) {
                var Kirby = {};
            }

            Kirby._params = {
                fm_key: '{{$fm_key}}',
            };

            @foreach ($content as $section)
                @foreach ($section as $component)
                    @if ($component->isComposite())
                        @foreach ($component->getJSConfiguration() as $name => $params)
                            Kirby._params["{{ $name }}"] = {!! json_encode($params) !!};
                        @endforeach
                    @else
                        <?php $name = substr(get_class($component), strrpos(get_class($component), '\\') + 1); ?>
                        Kirby._params["{{ $name }}"] = {!! json_encode($component->getJSConfiguration()) !!};
                    @endif
                @endforeach
            @endforeach
        </script>

        <?php $paths = []; ?>
        <?php $paths_external = []; ?>

        <?php // Ukljucivanje lokalnih js file-ova ?>
        @foreach ($js_local as $file)
            <?php $paths []= $file; ?>
        @endforeach
        @foreach ($js_external as $file)
            <?php $paths_external []= $file; ?>
        @endforeach

        @foreach ($content as $section)
            @foreach ($section as $component)
                <?php $js = $component->getJS(); ?>
                @foreach ($js as $file)
                    <?php $paths []= $file; ?>
                @endforeach
                <?php $js_external = $component->getJSExternal(); ?>
                @foreach ($js_external as $file)
                   <?php $paths_external []= $file; ?>
                @endforeach
            @endforeach
        @endforeach

        <?php $paths = array_unique($paths); ?>

        @if (config(php_uname('n').'.GOOGLE_ANALYTICS'))

            <script nonce = "{{$_SESSION['token']}}" async="" src="https://www.google-analytics.com/analytics.js"></script>
            <script nonce = "{{$_SESSION['token']}}"  type="text/javascript">
                <!--//--><![CDATA[//><!--
                (function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,"script","https://www.google-analytics.com/analytics.js","ga");ga("create", "UA-9374481-2", {"cookieDomain":"auto"});ga("send", "pageview");
                //--><!]]>
            </script>


            <!-- Facebook Pixel Code -->
            <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '898832827527148');
            fbq('track', 'PageView');
            </script>
            <noscript>
            <img height="1" width="1"
            src="https://www.facebook.com/tr?id=898832827527148&ev=PageView
            &noscript=1"/>
            </noscript>
            <!-- End Facebook Pixel Code -->
        @endif
        @if (MINIFIER_DISABLE)
            @foreach($paths as $path)
                <script src="/Components/{{ $path }}" nonce = "{{$_SESSION['token']}}"></script>
            @endforeach
        @else
            <script src="/min/?b=Components&f={{ implode($paths, ',') }}" nonce = "{{$_SESSION['token']}}" type="text/javascript" charset="utf-8" defer></script>
        @endif
        @foreach($paths_external as $path)
            <script src="{{ $path }}" nonce = "{{$_SESSION['token']}}" type="text/javascript"></script>
        @endforeach
        <script  type="text/javascript" nonce = "{{$_SESSION['token']}}">
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('service-worker.js').then(
                    function(reg) {},
                    function(error) {
                        console.log('Došlo je do greške: '+ error);
                    }
                );
            }
        </script>
</body>
</html>
