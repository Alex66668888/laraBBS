<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- 继承此模板的页面，如果没有定制 title 区域的话，就会自动使用第二个参数 LaraBBS 作为标题前缀。 -->
        <title>@yield('title', 'laraBBS') - Laravel 进阶练手</title>

        <!-- Styles -->
        <!-- 根据 webpack.mix.js 的逻辑来生成 CSS 文件链接。 -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">

        <!-- 种下 style 锚点 -->
        @yield('styles')

    </head>


    <body>

        <div id="app" class="{{ route_class() }}-page">

            @include('layouts._header')

            <div class="container">
                @include('shared._messages')

                <!-- 占位符声明，允许继承此模板的页面注入内容。 -->
                @yield('content')

            </div>

            @include('layouts._footer')

        </div>

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}"></script>

        <!-- 种下 script 锚点 -->
        @yield('scripts')

    </body>


</html>