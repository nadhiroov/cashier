function swalert(status = "", title = "", message = "") {
  swal(title, message, {
    icon: status,
    buttons: {
      confirm: {
        className: "btn btn-".status,
      },
    },
  });
}

function notif(status = "", title = "", message = "") {
  let content = {};
  content.message = message;
  content.title = title;
  if (status == "success") {
    content.icon = "fas fa-check";
  } else if (status == "error") {
    status = "danger"
    content.icon = "fas fa-times";
  }

  $.notify(content, {
    type: status,
    placement: {
      from: "top",
      align: "right",
    },
    autohide: true,
    time: 1000,
  });
}

function saveData(formSelection, successFunc = "") {
  let form = $(formSelection);
  // let data = $(form).serialize();
  let data = new FormData(formSelection);
  let url = $(form).attr("action");
  $.ajax({
    url: url,
    method: "POST",
    data: data,
    dataType: "json",
    processData: false,
    contentType: false,
    success: function (data) {
      notif(data.status, data.title, data.message);
      if (successFunc != "") {
        successFunc(data);
      } else {
        let text = $("#non-reset-text").val();
        let val = $("#non-reset-val").val();
        $("#addnew form :input").val("");
        if (text != "") {
          $("#non-reset-text").val(text);
        }
        if (val != "") {
          $("#non-reset-val").val(val);
        }
        $("#addnew").modal("hide");
        $("#edit").modal("hide");
        $("#datatable").DataTable().ajax.reload();
      }
    },
    error: function (err) {
      notif(err.status, err.title, err.message);
    },
  });
}

function detailRecord(selection) {
  var url = $(selection).attr("target");
  var id = $(selection).attr("data-id");
  $.ajax({
    url: url,
    type: "POST",
    data: {
      id: id,
    },
  });
}

function confirmDelete(selection, func = "") {
  var action = $(selection).attr("target");
  swal({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    type: "warning",
    buttons: {
      cancel: {
        visible: true,
        text: "Cancel",
        className: "btn btn-danger",
      },
      confirm: {
        text: "Yes, delete it!",
        className: "btn btn-success",
      },
    },
  }).then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url: action,
        type: "DELETE",
        dataType: "json",
        success: function (data) {
          notif(data.status, data.title, data.message);
          setTimeout(function () {
            swal.close();
          }, 2000);
          if (func != "") {
            func();
          }
          $("#datatable").DataTable().ajax.reload();
        },
        error: function (err) {
          notif(err.status, err.title, err.message);
        },
      });
    } else {
      swal.close();
    }
  });
}

function rupiah(nominal) {
  var rev = parseInt(nominal, 10).toString().split("").reverse().join("");
  var rev2 = "";
  for (var i = 0; i < rev.length; i++) {
    rev2 += rev[i];
    if ((i + 1) % 3 === 0 && i !== rev.length - 1) {
      rev2 += ".";
    }
  }
  return "Rp. " + rev2.split("").reverse().join("");
}
