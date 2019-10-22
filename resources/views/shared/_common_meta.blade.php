<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<meta name="description" content="@yield('description', 'Alex的个人博客')" />
<meta name="author" content="@yield('author', 'Alex')">

<!-- CSRF Token -->
{{-- 方便前端的 JavaScript 脚本获取 CSRF 令牌 --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title', 'Alex') - {{ config('app.name', '的个人博客') }}</title>
