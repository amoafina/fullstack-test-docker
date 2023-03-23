var page = 1,
  sortBy = 'id',
  sortDir = 'asc';

window.onload = function () {
  reload();

  $("#comment-form-submit").on('click', function () {
    addComment();
  });

  $("body").on('click', '.page-link', function (e) {
    e.preventDefault();
    setPage(getParameterByName('page', $(e.target).attr('href')));
    reload();
  });

  $("body").on('change', '#sort-select', function (e) {
    e.preventDefault();
    setSort($(e.target).val());
    reload();
  });

  $("body").on('click', '.comment-remove', function (e) {
    let commentId = $(e.target).parents('.card').attr('id').split('-')[1];
    removeComment(commentId);
  });
}

function setPage(newPage) {
  page = newPage;
}

function setSort(newSort) {
  let sortParams = newSort.split(':');
  sortBy = sortParams[0];
  sortDir = sortParams[1];
}

function getComments() {
  $.ajax({
    method: "GET",
    url: "/comments?page=" + page + "&sort_by=" + sortBy + "&sort_dir=" + sortDir,
    data: {},
    dataType: "json",
    beforeSend: function () {
      $('#main-preloader').show();
    },
    complete: function () {
      $('#main-preloader').hide();
    },
    success: function (comments) {
      let html = '';
      let template = document.querySelector('#comment-item');

      for (let i in comments) {
        html += interpolate(template.innerHTML, {
          id: comments[i].id,
          name: comments[i].name,
          text: comments[i].text,
          date: comments[i].date
        });
      }

      $('#comment-list').hide().html(html).fadeIn('slow');
    }
  });
}

function getPagination() {
  $(".pagination-block").load("/comments/pagination?page=" + page);
}

function reload() {
  getComments();
  getPagination();
}

function addComment() {
  let formData = {
    name: $('#comment-form input[name=\'name\']').val(),
    text: $('#comment-form textarea[name=\'text\']').val(),
    date: $('#comment-form input[name=\'date\']').val()
  }

  if (!validateEmail(formData.name)) {
    showErrors([
      {
        message: 'Email не валиден',
        selector: 'name'
      }
    ]);
  } else {
    $.ajax({
      method: "POST",
      url: "/comments",
      data: formData,
      dataType: "json",
      statusCode: {
        200: function () {
          clearForm();
          reload();
        },
        400: function (response) {
          showErrors(response.responseJSON.message);
        }
      }
    });
  }
}

function clearForm() {
  $('#comment-form input[name=\'name\']').val('');
  $('#comment-form textarea[name=\'text\']').val('');
  $('#comment-form input[name=\'date\']').val('');
}

function validateEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

function showErrors(errors) {
  removeErrors();
  let template = '<div class="invalid-feedback">${error}.</div>';
  let html = '';
  for (let i in errors) {
    html = interpolate(template, {
      error: errors[i].message
    });

    $('#input-comment-' + errors[i].selector).addClass('is-invalid');
    $('#input-comment-' + errors[i].selector).after(html);

  }
}

function removeErrors() {
  $('.invalid-feedback').remove();
  $('#comment-form input').removeClass('is-invalid');
  $('#comment-form textarea').removeClass('is-invalid');
}

function removeComment(id) {
  $.ajax({
    method: "DELETE",
    url: "/comments?id=" + id,
    dataType: "json",
    statusCode: {
      200: function () {
        reload();
      },
      400: function (json) {
        alert(json.message)
      },
      404: function (json) {
        alert(json.message)
      }
    }
  });
}

function interpolate(str, params) {
  let names = Object.keys(params);
  let vals = Object.values(params);
  return new Function(...names, `return \`${str}\`;`)(...vals);
}

function getParameterByName(name, url) {
  name = name.replace(/[\[\]]/g, '\\$&');
  var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
    results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, ' '));
}