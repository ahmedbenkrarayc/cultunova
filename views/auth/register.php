<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Sign up | Cultunova</title>
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
  <body  class=" d-flex flex-column">
    <script src="./../../dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page page-center">
      <div class="container container-tight py-4">
          <!-- <div class="alert alert-danger" role="alert" style="background: white;">
              <ul>
                  <li>erro1</li>
              </ul>
          </div> -->
        <div class="card card-md">
          <div class="card-body">
            <h2 class="h2 text-center mb-4">Create new account</h2>
            <form action="./../../" method="get" autocomplete="off" novalidate>
              <div class="mb-3">
                <label class="form-label">First name</label>
                <input type="text" class="form-control" placeholder="first name" name="fname" autocomplete="off">
              </div>
              <div class="mb-3">
                <label class="form-label">Last name</label>
                <input type="text" class="form-control" placeholder="last name" name="lname" autocomplete="off">
              </div>
              <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control" placeholder="your@email.com" name="email" autocomplete="off">
              </div>
              <div class="mb-2">
                <label class="form-label">Password</label>
                <div class="input-group input-group-flat">
                  <input type="password" class="form-control"  placeholder="Your password" name="password" autocomplete="off">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Role</label>
                <select class="form-select" name="role">
                  <option value="visitor">Visitor</option>
                  <option value="author">Author</option>
                </select>
              </div>
              <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Create new account</button>
              </div>
            </form>
          </div>
        </div>
        <div class="text-center text-secondary mt-3">
          Already have an account? <a href="./login.php" tabindex="-1">Sign in</a>
        </div>
      </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="./../../assets/js/validation.js"></script>
    <script src="./../../dist/js/tabler.min.js?1692870487" defer></script>
    <script src="./../../dist/js/demo.min.js?1692870487" defer></script>
  </body>
</html>