<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Categories | Cultunova</title>
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
        <div style="margin-inline: auto; width: 80%; margin-top: 50px;">
            <div class="alert alert-danger" role="alert" style="background: white;">
                <ul>
                    <li>erro1</li>
                </ul>
            </div>
            <div class="alert alert-success" role="alert" style="background: white;">Created successfully</div>
            <form class="card">
                <div class="card-header">
                  <h3 class="card-title">Edit Article</h3>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <label class="form-label required">Cover</label>
                    <div>
                      <input type="file" class="form-control" name="cover">
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label required">Title</label>
                    <div>
                      <input type="text" class="form-control" placeholder="Enter title" name="title">
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label required">Category</label>
                    <div>
                      <select class="form-control" name="category_id">
                        <option value="" disabled selected>Select a category</option>
                      </select>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label required">Description</label>
                    <div>
                      <textarea class="form-control" placeholder="Enter description" name="description"></textarea>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label required">Content</label>
                    <div>
                      <textarea class="form-control" placeholder="Enter content" name="content"></textarea>
                    </div>
                  </div>
                </div>
                <div class="card-footer text-end">
                  <button type="submit" class="btn btn-primary">Update</button>
                </div>
              </form>
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