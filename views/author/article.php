<?php
require_once './../../classes/Article.php';
require_once './../../classes/User.php';
require_once './../../classes/Article.php';
require_once './../../classes/Comment.php';
require_once './../../classes/Like.php';
require_once './../auth/user.php';

if(!User::verifyAuth()){
    header('Location: ./../auth/login.php');
}

if(!isset($_GET['id'])){
    header('Location: ./../auth/login.php');
}

$article = new Article($_GET['id'], null, null, null, null, null, null, null, null, null);
$currentArticle = $article->getOne();

if(!$currentArticle){
    header('Location: ./../visitor/home.php');
}


$comment = new Comment(null, $currentArticle['id'], null, null);
$comments = $comment->articleComments();

$tagsArticle = new ArticleTag($_GET['id'] ,null);
$allTags = $tagsArticle->tagsOfArticle() ?? [];

$like = new Like($currentArticle['id'], $GLOBALS['authUser']['id']);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['accept'])){
        $article->setStatus('accepted');
        $article->changeStatus();
        header('Location: '.$_SERVER['PHP_SELF'].'?id='.$_GET['id']);
    }

    if(isset($_POST['reject'])){
        $article->setStatus('rejected');
        $article->changeStatus();
    }

    if(isset($_POST['download'])){
        Article::generatePDF($currentArticle);
    }

    if(isset($_POST['comment'])){
        $comment->setVisitorId($_POST['visitor_id']);
        $comment->setArticleId($_POST['article_id']);
        $comment->setContent($_POST['content']);

        $comment->create();
        header('Location: '.$_SERVER['PHP_SELF'].'?id='.$_GET['id']);
    }

    if(isset($_POST['like'])){
        $like->likeArticle();
        header('Location: '.$_SERVER['PHP_SELF'].'?id='.$_GET['id']);
    }

    if(isset($_POST['unlike'])){
        $like->unlikeArticle();
        header('Location: '.$_SERVER['PHP_SELF'].'?id='.$_GET['id']);
    }
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Article | Cultunova</title>
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
                            <img style="height: 200px; object-fit: cover;" src="<?php echo $currentArticle['cover'] ?>" alt="">
                            <div class="card-body">
                                <div class="row g-2 align-items-center">
                                <div class="col">
                                    <h4 class="card-title m-0">
                                    <a href="./profile.php?id=<?php echo $currentArticle['author_id'] ?>"><?php echo $currentArticle['fname'].' '.$currentArticle['lname'] ?></a>
                                    </h4>
                                    <div class="text-secondary">
                                        <?php echo $like->articleLikes().' likes' ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <?php if($GLOBALS['authUser']['role'] == 'visitor'): ?>
                                        <form style="display: inline;" action="" method="post">
                                            <?php if($like->isLiked()): ?>
                                                <button type="submit" name="unlike" class="btn btn-primary">Unlike</button>
                                            <?php else: ?>
                                                <button type="submit" name="like" class="btn btn-outline-primary">Like</button>
                                            <?php endif; ?>
                                        </form>
                                    <?php endif; ?>
                                    <form style="display: inline;" action="" method="post">
                                        <button type="submit" name="download" class="btn btn-info">Download PDF</button>
                                    </form>
                                    <?php if($GLOBALS['authUser']['role'] == 'admin' && $currentArticle['status'] == 'in review'): ?>
                                    <form style="display: inline;" action="" method="post">
                                        <button type="submit" name="accept" class="btn btn-success">Accept</button>
                                    </form>
                                    <form style="display: inline;" action="" method="post">
                                        <button type="submit" name="reject" class="btn btn-danger">Reject</button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-cards" style="margin-top: 20px;">
                        <div class="col-lg-8">
                            <div class="card card-lg">
                                <div class="card-body">
                                    <?php echo $currentArticle['content'] ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                    <div class="me-3">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/scale -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 20l10 0" /><path d="M6 6l6 -1l6 1" /><path d="M12 3l0 17" /><path d="M9 12l-3 -6l-3 6a3 3 0 0 0 6 0" /><path d="M21 12l-3 -6l-3 6a3 3 0 0 0 6 0" /></svg>
                                    </div>
                                    <div>
                                        <h3 class="lh-1"><?php echo $currentArticle['title'] ?></h3>
                                    </div>
                                    </div>
                                    <div class="text-secondary mb-3">
                                        <div style="margin-bottom: 20px;">
                                            <?php foreach($allTags as $item): ?>
                                                <span class="badge bg-azure text-azure-fg"><?= $item['name'] ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php echo $currentArticle['description'] ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if($GLOBALS['authUser']['role'] != 'admin'): ?>
                        <div class="col-lg-8">
                            <div class="card card-lg">
                                <div class="card-body">
                                    <h1 class="lh-1">Comments</h1>
                                    <?php if($GLOBALS['authUser']['role'] == 'visitor'): ?>
                                    <form action="" method="post">
                                        <input type="hidden" name="visitor_id" value="<?php echo $GLOBALS['authUser']['id'] ?>">
                                        <input type="hidden" name="article_id" value="<?php echo $currentArticle['id'] ?>">
                                        <div class="mb-3">
                                            <textarea class="form-control" name="content" rows="6" placeholder="Write a comment ..."></textarea>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" name="comment" class="btn btn-primary">Comment</button>
                                        </div>
                                    </form>
                                    <?php endif; ?>
                                    <?php foreach($comments as $item): ?>
                                    <div class="card-body mt-4">
                                        <h3 class="card-title"><?php echo $item['fname'].' '.$item['lname'] ?></h3>
                                        <p class="card-subtitle"><?php echo $item['comment'] ?></p>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php require_once './../../utils/__footer.php' ?>
            </div>
        </div>
    </div>
    <!-- Libs JS -->
    <script src="./../../dist/libs/list.js/dist/list.min.js?1692870487" defer></script>
    <!-- Tabler Core -->
    <script src="./../../dist/js/tabler.min.js?1692870487" defer></script>
    <script src="./../../dist/js/demo.min.js?1692870487" defer></script>
  </body>
</html>