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
    title: "Anda yakin?",
    text: "Anda tidak dapat mengembalikan data yang sudah dihapus!",
    type: "warning",
    buttons: {
      cancel: {
        visible: true,
        text: "Batal!",
        className: "btn btn-danger",
      },
      confirm: {
        text: "Ya, hapus!",
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
