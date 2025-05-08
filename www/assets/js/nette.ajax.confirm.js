(function ($, undefined) {
	$.nette.ext({
		load: function () {
			$('[data-nette-confirm]').off('click.nette').click(function (event) {

				// remove old confirm dialog
				$("#dConfirmMaster").remove();

				var obj = this;
				event.preventDefault();
				event.stopImmediatePropagation();

				// modal dialog skeleton
				$("<div class='modal fade' id='dConfirmMaster' tabindex='-1' role='dialog' aria-labelledby='confirmModalLabel' aria-hidden='true'>" +
					"<div class='modal-dialog'>" +
					"	<div class='modal-content' id='dConfirm'>" +
					"		<div class='modal-header'>" +
					"			<h4 class='modal-title' id='confirmModalLabel'>" + $(obj).data('nette-confirm-title') + "</h4>" +
					"			<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>" +
					"		</div>" +
					"		<div class='modal-body'>" +
								$(obj).data('nette-confirm-text') +
					"		</div>" +
					"		<div class='modal-footer'>" +
					"			<button type='button' id='dConfirmOk' class='btn " + $(obj).data('nette-confirm-ok-class') + "' data-dismiss='modal'>Ano</button>" +
					"			<button type='button' id='dConfirmCancel' class='btn " + $(obj).data('nette-confirm-cancel-class') + "' data-dismiss='modal'>Ne</button>" +
					"		</div>" +
					"	</div>" +
					"</div>" +
					"</div>").appendTo('body');

				if ($(obj).data('nette-confirm-ok-text')) {
					$('#dConfirmOk').html($(obj).data('nette-confirm-ok-text'));
				}

				if ($(obj).data('nette-confirm-cancel-text')) {
					$('#dConfirmCancel').html($(obj).data('nette-confirm-cancel-text'));
				}

				$('#dConfirmOk').click(function () {
					var tagName = $(obj).prop("tagName");
					if (tagName == 'INPUT') {
						var form = $(obj).closest('form');
						form.submit();
					} else {
						if ($(obj).hasClass('ajax') || $(obj).data('ajax') == 'on') {
							$.nette.ajax({
								url: obj.href
							});
						} else {
							document.location = obj.href;
						}
					}
				});

				$('#dConfirmMaster').modal('show');

				return false;
			});
		}
	});
})(jQuery);