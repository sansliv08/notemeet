<script>
	// sem efeito
	$('#editModal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget) // Button that triggered the modal
		var id = button.data('id') // Extract info from data-* attributes
		var post_content = button.data('postcontent')
		var img = button.data('img')
		// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
		// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
		var modal = $(this)
		//modal.find('.modal-title').text('New message to ' + id)
		modal.find('#postid').val(id)
		modal.find('#postcontent').val(post_content)
		modal.find('#postimg').val(img)
	})

	$('#deleteModal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget)
		var id = button.data('id')
		var img = button.data('img')
		var modal = $(this)
		modal.find('#postid').val(id)
		modal.find('#postimg').val(img)
	})

	$('#editModalComment').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget)
		var id = button.data('id')
		var content = button.data('content')
		var modal = $(this)
		modal.find('#commentid').val(id)
		modal.find('#commentcontent').val(content)
	})

	$('#deleteModalComment').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget)
		var id = button.data('id')
		var parentid = button.data('parentid')
		var modal = $(this)
		modal.find('#commentid').val(id)
		modal.find('#parentid').val(parentid)
	})

</script>