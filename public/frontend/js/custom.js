
$('.uploadField').imageUploader({
	label: '',
	imagesInputName: 'Custom Name',
});



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


var expanded1 = false;

function showCheckboxes1() {
  var checkboxes = document.getElementById("scoopistDrop1");
  if (!expanded1) {
    $('#scoopistDrop1').slideDown();
    expanded1 = true;
  } else {
    $('#scoopistDrop1').slideUp();
    // checkboxes.style.display = "none";
    expanded1 = false;
  }
}



