/* $(function() {
    var options = { 
        bsort: true,
        aoColumnDefs: [
            {
                aTargets: [ 0 ],
                bSortable: false,
            },
			   {
                aTargets: [ 1 ],
                bSortable: false,
            },
			   {
                aTargets: [ 3 ],
                bSortable: false,
            },
			   {
                aTargets: [ 5 ],
                bSortable: false,
            },
			   {
                aTargets: [ 6 ],
                bSortable: false,
            },
            
          
        ]
    };
    
    $( 'table' ).dataTable( options );
}); */


/* $('#myTable').DataTable( {
    responsive: true
} ); */

$(document).on('click','.sub-menu > a', function(e){
    e.preventDefault();
    $(this).parent().toggleClass('close-menu').toggleClass('open-menu');
	$(this).parent().find(".inner-menu").toggle();
});

$('.sub-menu.active > a').trigger('click');

/*upload*/
function readURL(input) {
  if (input.files && input.files[0]) {

    var reader = new FileReader();

    reader.onload = function(e) {
      $('.image-upload-wrap').hide();

      $('.file-upload-image').attr('src', e.target.result);
      $('.file-upload-content').show();

      $('.image-title').html(input.files[0].name);
    };

    reader.readAsDataURL(input.files[0]);

  } else {
    removeUpload();
  }
}

function removeUpload() {
  $('.file-upload-input').replaceWith($('.file-upload-input').clone());
  $('.file-upload-content').hide();
  $('.image-upload-wrap').show();
}
$('.image-upload-wrap').bind('dragover', function () {
    $('.image-upload-wrap').addClass('image-dropping');
  });
  $('.image-upload-wrap').bind('dragleave', function () {
    $('.image-upload-wrap').removeClass('image-dropping');
});