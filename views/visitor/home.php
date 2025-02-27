<?php
require_once './../../classes/Category.php';
require_once './../../classes/User.php';
require_once './../auth/user.php';

if(!User::verifyAuth()){
  header('Location: ./../auth/login.php');
}

$category = new Category(null, null, null, null);
$categories = $category->categoryList() ?? [] ;
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Home | Cultunova</title>
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
    <?php require_once './../../utils/__header.php' ?>
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h2 class="page-title">
                  Articles
                </h2>
              </div>
            </div>
          </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
          <div class="container-xl">
            <div class="row g-4">
              <div class="col-3">
                <form id="form">
                  <div class="subheader mb-2">Find article</div>
                  <div class="mb-2">
                    <input type="text" placeholder="search ..." name="" class="form-control" id="keyword">
                  </div>
                  <div class="subheader mb-2">Category</div>
                  <div class="mb-2">
                    <select name="" class="form-select" id="category">
                      <option value="all">All</option>
                      <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="mt-5">
                    <button class="btn btn-primary w-100">
                      Search
                    </button>
                    <button class="btn btn-link w-100" id="reset">
                      Reset to defaults
                    </button>
                  </div>
                </form>
              </div>
              <div class="col-9">
                <div class="row row-cards" id="articlesContainer">

                  <!-- <div class="col-sm-6 col-lg-4">
                    <div class="card card-sm">
                      <a href="#" class="d-block"><img src="./../../static/photos/beautiful-blonde-woman-relaxing-with-a-can-of-coke-on-a-tree-stump-by-the-beach.jpg" class="card-img-top"></a>
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div>
                            <div>Title</div>
                            <div class="text-secondary">2025-01-01</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div> -->

                </div>
                <button id="showmore" style="background: black; color: white; border: none; outline: none; display: block; margin-inline:auto; padding:8px 16px;">Show more</button>
              </div>
            </div>
          </div>
        </div>
        <?php require_once './../../utils/__footer.php' ?>
      </div>
      <script src="./../../assets/js/visitor/home.js"></script>
    <!-- Libs JS -->
    <script src="./../../dist/libs/list.js/dist/list.min.js?1692870487" defer></script>
    <!-- Tabler Core -->
    <script src="./../../dist/js/tabler.min.js?1692870487" defer></script>
    <script src="./../../dist/js/demo.min.js?1692870487" defer></script>
  </body>
</html>