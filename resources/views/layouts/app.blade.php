<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('javascript')
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/fontawesome.css" integrity="sha384-jLKHWM3JRmfMU0A5x5AkjWkw/EYfGUAGagvnfryNV3F9VqM98XiIH7VBGVoxVSc7" crossorigin="anonymous" /> -->
    <link href="/css/layout.css" rel="stylesheet">
    <link href="/css/pp3.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            <div class="row">
                <div class="col-m1-1 p-0">
                    <div class="card">
                        <div class="card-header ml-1">タグ一覧</div>
                        <div class="card-body my-card-body">
                            <a href="/" class="card-text d-block">全て表示</a>
                            @foreach($tags as $tag)
                            <a href="/edit/{{$tag['id']}}" class="card-text d-block elipsis mb-2 ml-2">{{ $tag['name'] }}</a>
                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="col-md-2 p-0">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            メモ一覧<a href="{{route('home')}}"><i class="fa-solid fa-plus"></i></a>
                        </div>
                        <div class="card-body my-card-body">
                            @foreach($memos as $memo)
                            <a href="/edit/{{$memo['id']}}" class="card-text d-block elipsis mb-2">{{ $memo['content'] }}</a>
                            @endforeach
                        </div>
                    </div>

                </div>
                <div class="col-md-3 p-0">
                    <!-- 右カラム -->
                    @yield('content')
                </div>
                <div class="col-md-5 p-0">
                    <!-- 自作のjavascript.　want to list 可視化　の概要 -->
                    <div class="header">
                        <h2>My Careers</h2>
                        <a href="pp1.php">
                            <p>Homeに戻る</p>
                        </a>
                    </div>

                    <div class="body pt-3">
                        @foreach($memos as $memo)
                        <a href="#" class="d-block maru">
                            <p class="mb-0">{{ $memo['id'] }}//{{ $memo['content']}}</p>
                        </a>
                        @endforeach
                        <!-- <a href="#">Scroll</a> -->
                    </div>
                </div>
        </main>
    </div>
    <script src="/js/anime.min.js"></script>
    <script>
        const animation = [];
        // ① maruクリックで関数が一回だけ実行
        // ② maruクリックから変数bodyクリックでブロック作成
        // ③ animateBlocksでアニメーションを実行する
        //     緑の円50個生成
        //　for文で定義した数だけ、円生成
        const body = document.querySelectorAll('.maru')[0];
        body.addEventListener('click', function() {

            for (var i = 0; i <= 1; i++) {
                const blocks = document.createElement('div');
                blocks.classList.add('block');

                body.appendChild(blocks);
            }
        });

        // jQueryでクリックメソッドを利用する
        $(function() {
            $('.maru').one('click', animateBlocks)

        })
        'use strict'; {
            function animateBlocks() {
                anime({
                    targets: ".block",
                    translateX: function() {
                        return anime.random(0, 0);
                    },
                    targets: ".block",
                    translateY: function() {
                        return anime.random(130, 0);
                    },
                    scale: function() {
                        return anime.random(1, 3);
                    },

                    duration: 3000,
                    delay: anime.stagger(50),
                    complete: animateBlocks,

                });
            }
        }

        // ここからは近い繰り返し。付与されるタグに+1のクラス名表記する
        // 元々cssで作成、隠してある要素をクリックで出現、実行する処理を行う
        const two = document.querySelectorAll('.maru')[1];
        two.addEventListener('click', function() {

            for (var i = 0; i <= 1; i++) {
                const blocktwo = document.createElement('div');
                blocktwo.classList.add('blocktwo');

                two.appendChild(blocktwo);
            }
        });

        // 追加されたタスクのidがクリックされた場合div（ 円） を生成する。

        $(function() {
            $('.maru').one('click', animateBlockstwo)

        })
        'use strict'; {
            function animateBlockstwo() {
                anime({
                    targets: ".blocktwo",
                    translateX: function() {
                        return anime.random(0, 0);
                    },
                    targets: ".blocktwo",
                    translateY: function() {
                        return anime.random(130, 0);
                    },
                    scale: function() {
                        return anime.random(1, 3);
                    },

                    duration: 4000,
                    delay: anime.stagger(50),
                    complete: animateBlockstwo,

                });
            }
        }



        // 三つの目のblockthreeを付与、実行文
        const three = document.querySelectorAll('.maru')[2];
        three.addEventListener('click', function() {

            for (var i = 0; i <= 1; i++) {
                const blockthree = document.createElement('div');
                blockthree.classList.add('blockthree');

                three.appendChild(blockthree);
            }
        });

        // 追加されたタスクのidがクリックされた場合div（ 円） を生成する。
        // 生成divclass名　　実行function名　の変更が必須のため注意
        $(function() {
            $('.maru' [2]).one('click', animateBlocksthree)

        })
        'use strict'; {
            function animateBlocksthree() {
                anime({
                    targets: ".blockthree",
                    translateX: function() {
                        return anime.random(0, 0);
                    },
                    targets: ".blockthree",
                    translateY: function() {
                        return anime.random(130, 0);
                    },
                    scale: function() {
                        return anime.random(1, 3);
                    },

                    duration: 4500,
                    delay: anime.stagger(50),
                    complete: animateBlocksthree,

                });
            }
        }
    </script>
</body>

</html>