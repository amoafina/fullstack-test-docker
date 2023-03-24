<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Тестовое задание</title>
  <meta name="description" content="The small framework with powerful features">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" type="image/png" href="/favicon.ico" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="/css/stylesheet.css">
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="/js/app.js"></script>
</head>

<body>
  <div class="container">
    <div class="row d-flex justify-content-center mt-4">
      <div class="col-md-8 col-lg-6">
        <div class="card shadow-0 border" style="background-color: #f0f2f5;">
          <div class="card-body p-4">
            <div id="comment-list">
              <div id="preloader"></div>
            </div>
            <div class="navigation-block">
              <div class="pagination-block"></div>
              <div class="sort-block">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <label class="input-group-text">Сортировка</label>
                  </div>
                  <select id="sort-select">
                    <option selected value="id:asc">id по возрастанию</option>
                    <option value="id:desc">id по убыванию</option>
                    <option value="date:asc">дата по возрастанию</option>
                    <option value="date:desc">дата по убыванию</option>
                  </select>
                </div>
              </div>
            </div>
            <hr />
            <div class="form-outline mb-4" id="comment-form">
              <div class="row d-flex justify-content-center mb-3">
                <div class="col-md-6">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">@</div>
                    </div>
                    <input id="input-comment-name" type="text" name="name" class="form-control" placeholder="Email" />
                  </div>
                </div>
                <div class="col-md-6"><input id="input-comment-date" type="datetime-local" name="date" class="form-control" placeholder="Дата" /></div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12"><textarea id="input-comment-text" name="text" class="form-control" placeholder="Комментарий"></textarea></div>
              </div>
              <button type="button" id="comment-form-submit" class="btn btn-primary btn-block">Отправить</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <template id="comment-item">
    <div id="comment-${id}" class="card mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="d-flex flex-row">
            <p>${text}</p>
          </div>
          <div class="d-flex flex-row">
            <p class="comment-remove">&times;</p>
          </div>
        </div>
        <div class="d-flex justify-content-between">
          <div class="d-flex flex-row align-items-center">
            <p class="small mb-0 ms-2">${name}</p>
          </div>
          <div class="d-flex flex-row align-items-center">
            <p class="small text-muted mb-0">${date}</p>
          </div>
        </div>
      </div>
    </div>
  </template>

  <template id="empty-comment-item">
    <p class="text-center text-muted">Комментариев пока нет... :(</p>
  </template>
</body>
</html>