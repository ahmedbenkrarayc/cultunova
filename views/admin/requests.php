<?php 
require_once './../../classes/Article.php';
require_once './../../classes/User.php';
require_once './../auth/user.php';

if(!User::verifyAuth('admin')){
  header('Location: ./../auth/login.php');
}

$article = new Article(null, null, null, null, null, null, null, null, null, null);
$articles = $article->getAll() ?? [] ;

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Article posting requests | Cultunova</title>
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
        <!-- Page header -->
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h2 class="page-title">
                  Request list
                </h2>
              </div>
            </div>
          </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
          <div class="container-xl">
            <div class="card">
              <div class="card-body">
                <div id="table-default" class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th><button class="table-sort">#</button></th>
                        <th><button class="table-sort">title</button></th>
                        <th><button class="table-sort">Description</button></th>
                        <th><button class="table-sort">Created at</button></th>
                        <th><button class="table-sort">Updated at</button></th>
                        <th><button class="table-sort">Actions</button></th>
                      </tr>
                    </thead>
                    <tbody class="table-tbody">
                      <?php 
                      foreach($articles as $index => $item) {
                        if($item['status'] == 'in review') {
                          echo '
                            <tr>
                              <td class="sort-name">'.($index+1).'</td>
                              <td class="sort-city">'.$item['title'].'</td>
                              <td class="sort-type">'.$item['description'].'</td>
                              <td class="sort-score">'.explode(' ',$item['createdAt'])[0].'</td>
                              <td class="sort-score">'.explode(' ',$item['updatedAt'])[0].'</td>
                              <td class="sort-date">
                                  <a href="./../author/article.php?id='.$item['id'].'">details</a>
                              </td>
                            </tr>
                          ';
                        }
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
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