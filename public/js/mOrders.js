var ManageCupon = function() {
  var cuponManage = function() {

      var orderDatatable = $('#m_orders_datatable');

      orderDatatable.mDatatable({
        // datasource definition
        data: {
          type: 'remote',
          source: {
  			read: {
  				url: '/admin/order/getAllData',
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
            field: 'order_username',
            title: 'User',
            width: 100,
          },  {
            field: 'order_email',
            title: 'Email',
            width: 150,
          }, {
            field: 'order_city',
            title: 'Location',
            width: 200,
            template: function(row, index, datatable){
                return row.order_street+', '+row.order_city+' '+row.order_zip;
            }
          }, {
            field: 'order_time',
            title: 'Time',
            width: 150,
          }, {
            field: 'total_price',
            title: 'Price',
            width: 100,
            template: function(row, index, datatable){
                return row.total_price+' €';
            }
          }, {
            field: 'payment_method',
            title: 'Payment method',
            width: 150,
            template: function(row, index, datatable){
                var pmethod = 'Online Payment';
                if (row.payment_method == 1) {
                    pmethod = 'Cash Payment';
                }
                return pmethod;
            }
          }, {
            field: 'is_payed',
            title: 'Is Payed',
            width: 100,
            template: function(row, index, datatable){
                var status = 'False';
                if (row.is_payed == 1) {
                    status = 'True';
                }
                return status;
            }
          }, {
              field: "Actions",
              width: 70,
              title: "Actions",
              sortable: false,
              overflow: 'visible',
              template: function (row, index, datatable) {
                  return '\
                      <a href="/admin/order/detail/'+row.id+'" target="_blank" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="View Detail ">\
                      <i class="la la-edit"></i>\
                      </a>\
                  ';
              }
          }],
      });
  };

  return {
    // public functions
    init: function() {
      cuponManage();
    },
  };
}();

jQuery(document).ready(function() {
  ManageCupon.init();
});
