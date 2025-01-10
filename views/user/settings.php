<?php
require_once './../../classes/User.php';
require_once './../../classes/Author.php';
require_once './../auth/user.php';
require_once './../../exceptions/InputException.php';

if(!User::verifyAuth()){
    header('Location: ./../auth/login.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['update'])){
        if($GLOBALS['authUser']['role'] == 'author'){
            if(isset($_FILES['cover']) && $_FILES['cover']['error'] == 0){
                $picName = 'image'.time().rand(1000, 9999).'.'.pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
                move_uploaded_file($_FILES['cover']['tmp_name'], './../../assets/uploads/'.$picName);
                $cover = '/assets/uploads/'.$picName;
            }else{
                $cover = null;
            }

            if(isset($_FILES['picture']) && $_FILES['picture']['error'] == 0){
                $picName = 'image'.time().rand(1000, 9999).'.'.pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
                move_uploaded_file($_FILES['picture']['tmp_name'], './../../assets/uploads/'.$picName);
                $picture = '/assets/uploads/'.$picName;
            }else{
                $picture = null;
            }

            $user = new Author($GLOBALS['authUser']['id'], $_POST['fname'], $_POST['lname'], null, $_POST['password'], null, null, $picture, $cover, null);
        }else{
            $user = new User($GLOBALS['authUser']['id'], $_POST['fname'], $_POST['lname'], null, $_POST['password'], null, null);
        }

        $errors = $user->getErrors();
        if(count($errors) == 0){
            try{
                if($user->updateProfile()){
                    $errors = $user->getErrors();
                }else{
                    $success = true;
                }
            }catch(InputException $e){
                $errors[] = $e->getMessage();
            }
        }
    }
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Settings | Cultunova</title>
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
                <div id="errors" class="alert alert-danger" role="alert" style="background: white; display: none;">
                    <ul></ul>
                </div>
                <?php if(isset($errors) && count($errors) > 0): ?>
                <div class="alert alert-danger" role="alert" style="background: white;">
                    <ul>
                        <?php
                        foreach($errors as $error){
                            echo '<li>'.$error.'</li>';
                        }
                        ?>
                    </ul>
                </div>
                <?php endif; ?>
                <?php if(isset($success) && $success): ?>
                    <div class="alert alert-success" role="alert" style="background: white;">Updated successfully</div>
                <?php endif; ?>
                    <div class="row row-cards" style="margin-top: 20px;">
                    <div class="col-12">
                            <form action="" method="POST" class="card" enctype="multipart/form-data">
                                <div class="card-header">
                                    <h3 class="card-title">My Settings</h3>
                                </div>
                                <div class="card-body">
                                    <?php if($GLOBALS['authUser']['role'] == 'author'): ?>
                                    <div class="mb-3">
                                        <label class="form-label">Profile cover</label>
                                        <input type="file" class="form-control" name="cover">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Profile picture</label>
                                        <input type="file" class="form-control" name="picture">
                                    </div>
                                    <?php endif; ?>
                                    <div class="mb-3">
                                        <label class="form-label">First name</label>
                                        <input class="form-control" name="fname" value="<?php echo $GLOBALS['authUser']['fname'] ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Last name</label>
                                        <input class="form-control" name="lname" value="<?php echo $GLOBALS['authUser']['lname'] ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">New Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="password">
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button type="submit" name="update" class="btn btn-primary">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
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