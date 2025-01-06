<?php
require_once './../../classes/Article.php';
require_once './../../classes/User.php';
if(!isset($_GET['id'])){
    header('Location: ./../auth/login.php');
}

$user = new User($_GET['id'], null, null, null, null, null, null, null);
$author = $user->getUser();
if(!$author){
    header('Location: ./../auth/login.php');
}else{
    if($author['role'] != 'author'){
        header('Location: ./../auth/login.php');
    }
}

$article = new Article(null, null, null, null, null, null, null, $_COOKIE['user_id'], null, null);
$articles = $article->articleByAuthor() ?? [];
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title><?php echo $author['fname'].' '.$author['lname'] ?> | Cultunova</title>
    <!-- CSS files -->
    <link href="./../../dist/css/tabler.min.css?1692870487" rel="stylesheet"/>
    <link href="./../../dist/css/tabler-flags.min.css?1692870487" rel="stylesheet"/>
    <link href="./../../dist/css/tabler-payments.min.css?1692870487" rel="stylesheet"/>
    <link href="./../../dist/css/tabler-vendors.min.css?1692870487" rel="stylesheet"/>
    <link href="./../../dist/css/demo.min.css?1692870487" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }
    </style>
  </head>
  <body >
    <script src="./../../dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page">
        <!-- header here -->
        <?php require_once './../../utils/__header.php' ?>
        <div class="page-wrapper">
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    <div class="row row-cards">
                        <div class="col-md-12">
                            <div class="card">
                            <img style="height: 200px; object-fit: cover;" src="https://images.unsplash.com/photo-1730119986244-eb33b57b3950?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="">
                            <div class="card-body">
                                <div class="row g-2 align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-lg" style="background-image: url(./static/avatars/000m.jpg)"></span>
                                </div>
                                <div class="col">
                                    <h4 class="card-title m-0">
                                    <a href="#"><?php echo $author['fname'].' '.$author['lname'] ?></a>
                                    </h4>
                                    <div class="text-secondary">
                                    Author
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="dropdown">
                                    <a href="#" class="btn-action" data-bs-toggle="dropdown" aria-expanded="false">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/dots-vertical -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
                                    </a>
                                    <?php if($_COOKIE['user_role'] == 'admin'): ?>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="#" class="dropdown-item text-danger">Delete account</a>
                                    </div>
                                    <?php endif; ?>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row row-cards" style="margin-top:40px;">
                        <?php foreach($articles as $item): ?>
                        <div class="col-sm-6 col-lg-4">
                            <div class="card card-sm">
                                <a href="./article.php?id=<?php echo $item['id'] ?>" class="d-block"><img src="<?php echo $item['cover'] ?>" class="card-img-top"></a>
                                <div class="card-body">
                                    <div class="d-flex align-items-center" style="justify-content: space-between;">
                                        <div>
                                            <div><?php echo $item['title'] ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="dropdown">
                                                <a href="#" class="btn-action" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <!-- Download SVG icon from http://tabler-icons.io/i/dots-vertical -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
                                                </a>
                                                <?php if($_COOKIE['user_id'] == $_GET['id']): ?>
                                                <div class="dropdown-menu dropdown-menu-end" style="">
                                                    <a href="./editarticle.php?id=<?php echo $item['id'] ?>" class="dropdown-item">Edit</a>
                                                    <a href="#" class="dropdown-item text-danger">Delete</a>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                </div>
            </div>
            <?php require_once './../../utils/__footer.php' ?>
        </div>
    </div>
    <!-- Libs JS -->
    <script src="./../../dist/libs/list.js/dist/list.min.js?1692870487" defer></script>
    <!-- Tabler Core -->
    <script src="./../../dist/js/tabler.min.js?1692870487" defer></script>
    <script src="./../../dist/js/demo.min.js?1692870487" defer></script>
  </body>
</html>