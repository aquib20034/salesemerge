$('.checkboxWrap a').click(function(){
	$(this).next('.radioListDropDown').slideToggle();
	$(this).children('span.checkboxDropIcon').toggleClass('active');
})
$('.uploadField').imageUploader({
	label: '',
	imagesInputName: 'Custom Name',
});

// $('.selectScoopist').select2();



var expanded = false;

function showCheckboxes() {
  var checkboxes = document.getElementById("scoopistDrop");
  if (!expanded) {
    $('#scoopistDrop').slideDown();
    expanded = true;
  } else {
    $('#scoopistDrop').slideUp();
    // checkboxes.style.display = "none";
    expanded = false;
  }
}

$('label.selectAll input[type="checkbox"]').click(function(){
	// $(this).siblings('label').find('input').attr('checked');
	if(this.checked) {
        // Iterate each checkbox
        $('.scoopistCheckList').each(function() {
            this.checked = true;                        
        });
    } else {
        $('.scoopistCheckList').each(function() {
            this.checked = false;                       
        });
    }
})

$('.radioBox input.radioServicesOne').click(function(){
	// $('.radioBox').removeClas('checked');
	// $(this).parent('.radioBox').toggleClass('checked');
	let selectedServe1 = $(this).parent('.radioBox');
	$('.selectedServices1').html(selectedServe1);
	// $(this).props('checked');

	console.log('Selected soft--------', selectedSoft);
})