var ManageCategory = function() {
  var categoryManage = function() {

      var categoryDatatable = $('#m_categories_datatable');

      categoryDatatable.mDatatable({
        // datasource definition
        data: {
          type: 'remote',
          source: {
  			read: {
  				url: '/admin/category/getAllDatas',
                  method: 'GET',
  			},
  		},
          pageSize: 5,
        },

        // column sorting
        sortable: true,

        pagination: true,

        toolbar: {
          // toolbar items
          items: {
            // pagination
            pagination: {
              // page size select
              pageSizeSelect: [5, 10, 20, 30, 50, 100],
            },
          },
        },

        // columns definition
        columns: [
           {
            field: 'image',
            title: 'Image',
            width: 100,
            template: function(row, index, datatable) {
                return '<img style="width: 100%;" src="/uploads/category/'+row.image+'" />'
            }
          }, {
            field: 'title',
            title: 'Title',
            width: 150,
          }, {
            field: 'description',
            title: 'Description',
            width: 500
          }, {
            field: 'position',
            title: 'Position',
            width: 100
          }, {
              field: "Actions",
              width: 120,
              title: "Actions",
              sortable: false,
              overflow: 'visible',
              template: function (row, index, datatable) {
                  var dropup = (datatable.getPageSize() - index) <= 4 ? 'dropup' : '';

                  return '\
                      <a href="javascript:;" data-category_id="'+row.id+'" class="category_delete_btn m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete ">\
                      <i class="la la-trash"></i>\
                      </a>\
                      <a href="javascript:;" data-category_id="'+row.id+'" class="category_edit_btn m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Delete ">\
                      <i class="la la-edit"></i>\
                      </a>\
                      <a href="/admin/category/product/'+row.id+'" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Delete ">\
                      <i class="la la-plus"></i>\
                      </a>\
                  ';
              }
          }],
      });

      var categoryAddForm = $('#new_category_add_form');

      var categoryAddFormValid = categoryAddForm.validate({
          rules: {
              cat_title: {
                  required: true
              },
              cat_description: {
                  required: true,
              },
              slim: {
                  required: true,
              }
          },
          messages: {},
          errorPlacement: function(error, element) {
          }
      });

      categoryAddForm.on('submit', function(e) {
          e.preventDefault();

          var form = $(this);

          if (!categoryAddFormValid.form()) {
              swal({
                  title: "",
                  text: "Please add correct data.",
                  type: "error",
                  confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
              });

              return false;
          };

          $.ajaxSetup({
              headers: {
                  'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
              }
          });

          var url = form.attr('action');

          var formData = new FormData(form[0]);
          var submit_btn = form.find('.form-submit-btn');
          submit_btn.addClass('m-loader m-loader--right m-loader--accent').attr('disabled', true);

          $.ajax({
              url: url,
              type: 'POST',
              data: formData,
              success: function(response) {
                  submit_btn.removeClass('m-loader m-loader--right m-loader--accent').attr('disabled', false);
                  console.log(response)
                  if (response.result === "success") {
                      $('#new_category_add_modal').modal('hide');
                      categoryDatatable.reload();
                  } else {
                      swal({
                          title: "Error",
                          text: response.msg,
                          type: "error",
                          confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
                      });
                  }
              },
              processData: false,
              contentType: false,
              error: function(error) {
                  console.log(error);
              }
          });
      })

      $(document).on('click', '.category_edit_btn', function() {
          var categoryId = $(this).data('category_id');

          $.ajax({
              url: '/admin/category/getCategoryData/'+categoryId,
              type: 'get',
              success: function(response){
                  if (response.result == "success") {
                      var editForm = $('#exist_category_edit_form');
                      editForm.find('#edit_category_id').val(response.category.id);
                      editForm.find('#_cat_title').val(response.category.title);
                      editForm.find('#_cat_description').val(response.category.description);
                      console.log(response.category.image_url);
                      $('#edit_category_image_slim>img').attr('src', response.category.image_url);
                      categoryEditImgSlim = new Slim(document.getElementById('edit_category_image_slim'), {
                          ratio: '1:1',
                          minSize: {
                              width: 100,
                              height: 100
                          },
                          download: false,
                          label: 'Drop your image here or Click',
                          statusImageTooSmall: 'Image too small. Min Size is $0 pixel. Try again.'
                      });

                      categoryEditImgSlim.size = {
                          width: 300,
                          height: 300
                      };

                      $('#exist_category_edit_modal').modal('show');
                  } else if (response.result == "error") {
                      swal({
                          "title": "Faild",
                          "text": "something went wrong !.",
                          "type": "error",
                          "confirmButtonClass": "btn m-btn--air m-btn btn-outline-accent m-btn--wide"
                      });
                  }
              },
              error: function(error){
                  console.log(error);
              }
          });
      });

      var categoryEditForm = $('#exist_category_edit_form');

      var categoryEditFormValid = categoryEditForm.validate({
          rules: {
              _cat_title: {
                  required: true
              },
              _cat_description: {
                  required: true,
              },
          },
          messages: {},
          errorPlacement: function(error, element) {
          }
      });

      categoryEditForm.on('submit', function(e) {
          e.preventDefault();

          var form = $(this);

          if (!categoryEditFormValid.form()) {
              swal({
                  title: "",
                  text: "Please add correct data.",
                  type: "error",
                  confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
              });

              return false;
          };

          $.ajaxSetup({
              headers: {
                  'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
              }
          });

          var url = form.attr('action');

          var formData = new FormData(form[0]);
          var submit_btn = form.find('.form-submit-btn');
          submit_btn.addClass('m-loader m-loader--right m-loader--accent').attr('disabled', true);

          $.ajax({
              url: url,
              type: 'POST',
              data: formData,
              success: function(response) {
                  submit_btn.removeClass('m-loader m-loader--right m-loader--accent').attr('disabled', false);
                  console.log(response)
                  if (response.result === "success") {
                      $('#exist_category_edit_modal').modal('hide');
                      categoryDatatable.reload();
                  } else {
                      swal({
                          title: "Error",
                          text: response.msg,
                          type: "error",
                          confirmButtonClass: "btn m-btn--air m-btn btn-outline-accent m-btn--wid"
                      });
                  }
              },
              processData: false,
              contentType: false,
              error: function(error) {
                  console.log(error);
              }
          });
      });

      $(document).on('click', '.category_delete_btn', function(){
          var $this = $(this);

          swal({
              title: 'Are you sure?',
              text: "Delete Category !",
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: ' Yes !',
              confirmButtonClass: "btn m-btn--air btn-outline-danger m-btn m-btn--wide",
              cancelButtonClass: "btn m-btn--air btn-outline-primary m-btn m-btn--wide",
          }).then(function(result) {
              if (result.value) {
                  var categoryId = $this.data('category_id');
                  $.ajax({
                      url: '/admin/category/destroy/'+categoryId,
                      type: 'get',
                      success: function(response){
                          if (response.result == "success") {
                              swal({
                                  "title": "Success",
                                  "text": "Category Deleted !.",
                                  "type": "success",
                                  "confirmButtonClass": "btn m-btn--air m-btn btn-outline-accent m-btn--wide"
                              });
                              categoryDatatable.reload();
                          } else if (response.result == "error") {
                              swal({
                                  "title": "Faild",
                                  "text": response.msg,
                                  "type": "error",
                                  "confirmButtonClass": "btn m-btn--air m-btn btn-outline-accent m-btn--wide"
                              });
                          }
                      },
                      error: function(error){
                          console.log(error);
                      }
                  });
              }
          });
      });

      var local_category_sortable;

      var byId = function (id) {
          return document.getElementById(id);
      };

      $('#category_sort_model_open_btn').on('click', function(e) {
          $.ajax({
              url: '/admin/category/getAllDatas',
              type: 'get',
              success: function(response){
                  if (response.length > 0) {
                      $('#category_sortable_ul').html("");
                      response.forEach(function(category) {
                          var categoryHtml = '<li data-id="'+category.id+'">'+category.title+'</li>';
                          $('#category_sortable_ul').append(categoryHtml);
                      });

                      localStorage.setItem(local_category_sortable, '');

                      Sortable.create(byId('category_sortable_ul'), {
                          group: "words",
                          animation: 150,
                          store: {
                              get: function (sortable) {
                                  var order = localStorage.getItem(local_category_sortable);
                                  var splited_order = order ? order.split('|') : [];
                                  return splited_order;
                              },
                              set: function (sortable) {
                                  var order = sortable.toArray();
                                  localStorage.setItem(local_category_sortable, order.join('|'));
                              }
                          },
                      });

                      $('#category_sortable_modal').modal('show');
                  } else {
                      swal({
                          "title": "Faild",
                          "text": "something went wrong !.",
                          "type": "error",
                          "confirmButtonClass": "btn m-btn--air m-btn btn-outline-accent m-btn--wide"
                      });
                  }
              },
              error: function(error){
                  console.log(error);
              }
          });
      });

      $('#category_sortable_form').on('submit', function(e) {
          e.preventDefault();
          var form = $(this);
          var order = localStorage.getItem(local_category_sortable);
          var splited_order = order ? order.split('|') : [];
          var order_url = "/admin/category/setOrder";
          $.ajaxSetup({
              headers: {
                  'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
              }
          });

          if (splited_order.length > 0) {
              var submit_btn = form.find('.form-submit-btn');
              submit_btn.addClass('m-loader m-loader--right m-loader--accent').attr('disabled', true);

              $.post(
                  order_url,
                  {'sort_list': splited_order},
                  function(data, status){
                      submit_btn.removeClass('m-loader m-loader--right m-loader--accent').attr('disabled', false);
                      if (status == "success") {
                          $('#category_sortable_modal').modal('hide');
                          categoryDatatable.reload();
                      }
                  }
              );
          } else {
              swal({
                  "title": "Alert",
                  "text": "Please Update Order.",
                  "type": "warning",
                  "confirmButtonClass": "btn m-btn--air m-btn btn-outline-accent m-btn--wide"
              });
          }
      });
  };

  var slimInit = function() {
      categoryImgSlim = new Slim(document.getElementById('category_image_slim'), {
          ratio: '1:1',
          minSize: {
              width: 100,
              height: 100
          },
          download: false,
          label: 'Drop your image here or Click',
          statusImageTooSmall: 'Image too small. Min Size is $0 pixel. Try again.'
      });

      categoryImgSlim.size = {
          width: 300,
          height: 300
      };
  }

  var slimDestory = function() {
      categoryImgSlim.destroy();
      categoryEditImgSlim.destroy();
  }

  return {
    // public functions
    init: function() {
        var categoryImgSlim,
        categoryEditImgSlim;
        categoryManage();
        slimInit();
    },
  };
}();

jQuery(document).ready(function() {
  ManageCategory.init();
});
