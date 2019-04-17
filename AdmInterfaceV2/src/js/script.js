$(document).ready(function () {
	console.log('The DOM is ready');

	// Tooltip
	$('body').tooltip({
		selector: '[data-toggle="tooltip"]'
	});

	$("#chk-newsid").change(function () {
		$("#lalala").toggleClass("d-none");
	});

	$('#sectionModalEdit').on('shown.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var section = button.data('section');

		var modal = $(this);
		modal.find('.modal-body input#sectionName').val(section);
	});

	// Quiz Mode option
	$('body').on('click', '#opt-freeflow', function () {
		$('#freeflowOptions').css("display", "flex");
		$('#opt-freeflow').attr('selected');
		$('#opt-segments').removeAttr('selected');
		$('.opt-quizMode:eq(0)').addClass('active');
		$('.opt-quizMode:eq(1)').removeClass('active');

		if ($('.opt-quizMode:eq(0)').hasClass('active')) {
			if ($('.opt-quizMode:eq(0)').hasClass('btn-primary')) {
				$('.opt-quizMode:eq(0)').removeClass('btn-primary');
				$('.opt-quizMode:eq(0)').addClass('btn-primary');
				$('.opt-quizMode:eq(0)').removeClass('btn-light');
				$('.opt-quizMode:eq(1)').removeClass('btn-primary');
				$('.opt-quizMode:eq(1)').addClass('btn-light');
			} else {
				$('.opt-quizMode:eq(0)').addClass('btn-primary');
				$('.opt-quizMode:eq(0)').removeClass('btn-light');
				$('.opt-quizMode:eq(1)').removeClass('btn-primary');
				$('.opt-quizMode:eq(1)').addClass('btn-light');
			}
		}

		console.log('Freeflow selected');
	});

	$('body').on('click', '#opt-segments', function () {
		$('#freeflowOptions').css("display", "none");
		$('#opt-segments').attr('selected');
		$('#opt-freeflow').removeAttr('selected');
		$('.opt-quizMode:eq(1)').addClass('active');
		$('.opt-quizMode:eq(0)').removeClass('active');

		if ($('.opt-quizMode:eq(1)').hasClass('active')) {
			if ($('.opt-quizMode:eq(1)').hasClass('btn-primary')) {
				$('.opt-quizMode:eq(1)').removeClass('btn-primary');
				$('.opt-quizMode:eq(1)').addClass('btn-primary');
				$('.opt-quizMode:eq(1)').removeClass('btn-light');
				$('.opt-quizMode:eq(0)').removeClass('btn-primary');
				$('.opt-quizMode:eq(0)').addClass('btn-light');
			} else {
				$('.opt-quizMode:eq(1)').addClass('btn-primary');
				$('.opt-quizMode:eq(1)').removeClass('btn-light');
				$('.opt-quizMode:eq(0)').removeClass('btn-primary');
				$('.opt-quizMode:eq(0)').addClass('btn-light');
			}
		}

		console.log('Segments selected');
	});

	// Quiz Host Timer
	// $('#circleTimer').circleProgress({
	// 	value: 0.69,
	// 	size: 100,
	// 	startAngle: Math.PI * 1.5,
	// 	fill: {
	// 		gradient: ["#4c84ff", "#43eabc"]
	// 	}
	// });


});
var $radio = $("input:radio");
$radio.change(function () {
    if ($radio.filter(':checked').length > 0) {
        $("#mataposkana").removeAttr("disabled");
    } else {
        $("#mataposkana").attr("disabled", "disabled");
    }
});
// Text remaining counter
// function showTxtRemaining(elemToCount, textFeedback, textLimit) {
// 	console.log('Text remaining will be displayed', elemToCount, textFeedback, textLimit);
// 	$(textFeedback).text(textLimit).css("display", "block");
// 	$(elemToCount).keyup(function () {
// 		var text_length = $(elemToCount).val().length;
// 		var text_remaining = textLimit - text_length;

// 		$(textFeedback).text(text_remaining);
// 	});
// }

// function hideMe(elemToHide) {
// 	$(elemToHide).css("display", "none");
// }

let tags = [];

function addTag(event, el) {
	var key_press = (event.keyCode ? event.keyCode : event.which);

	let hiddenInput = document.getElementById('addQ-hidden-input'),
		mainInput = document.getElementById('addQ-main-input'),
		tagsInput = document.getElementsByClassName('tags-input')[0];

	if (key_press == 13) {
		let tag = {
			text: mainInput.value,
			element: document.createElement('span'),
		}

		tag.element.classList.add('tag');
		tag.element.textContent = tag.text;

		let closeBtn = document.createElement('span');
		closeBtn.classList.add('tagClose');
		closeBtn.addEventListener('click', function () {
			removeTag(tags.indexOf(tag));
		});
		tag.element.appendChild(closeBtn);

		tags.push(tag);

		tagsInput.insertBefore(tag.element, mainInput);

		mainInput.value = "";

		refreshTags();
	}

	function removeTag(index) {
		let tag = tags[index];
		tags.splice(index, 1);
		tagsInput.removeChild(tag.element);
		refreshTags();
	}

	function refreshTags() {
		let tagsList = [];
		tags.forEach(function (t) {
			tagsList.push(t.text);
		});

		hiddenInput.value = tagsList.join(',');
	}
}

// Sortable
var sortableStudent = document.getElementById('sort-arrange');
var sortable = Sortable.create(sortableStudent);