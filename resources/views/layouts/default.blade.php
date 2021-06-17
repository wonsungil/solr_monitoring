<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app" v-cloak>
    <main class="py-4">
        <b-menu>
            <b-menu-list>
                <b-menu-item icon="information-outline" label="SolrCloud">
                    <b-menu-item icon="account" label="샤드 리스트" tag="a" href="/sc/shards"></b-menu-item>
                    <b-menu-item icon="account" label="샤드 수" tag="a" href="/sc/shardCntgi"></b-menu-item>
                    <b-menu-item icon="account" label="노드 리스트" tag="a" href="/sc/nodes"></b-menu-item>
                    <b-menu-item icon="account" label="노드 정보" tag="a" href="/sc/nodeInfo"></b-menu-item>
                    <b-menu-item icon="account" label="명령 및 에러" tag="a" href="/sc/operations"></b-menu-item>
                    <b-menu-item icon="account" label="실행 Job" tag="a" href="/sc/runningMap"></b-menu-item>
                    <b-menu-item icon="account" label="State.json" tag="a" href="/sc/state"></b-menu-item>
                    <b-menu-item icon="account" label="디스크 사용량별 샤드수" tag="a" href="/sc/diskUsageGroupBy"></b-menu-item>
                </b-menu-item>

                <b-menu-item icon="information-outline" label="Insight Solr">
                    <b-menu-item icon="account" label="Doc Count" tag="a" href="/check"></b-menu-item>
                </b-menu-item>
            </b-menu-list>
        </b-menu>
        <hr>
        @yield('content')
    </main>
</div>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>
@yield('scripts')
</body>
</html>
