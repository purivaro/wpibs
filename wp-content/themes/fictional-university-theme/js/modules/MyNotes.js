import $ from 'jquery';
import axios from 'axios';

class MyNotes {
	// Init
	constructor() {
		this.events()
	}

	// Events
	events() {
		// document.querySelectorAll(".delete-note").forEach((i) => {
		// 	i.addEventListener("click", (e) => {
		// 		if(confirm('Are you sure?')){
		// 			this.deleteNote(e.currentTarget)
		// 		}
		// 	})
		// })

		$(document).on("click", ".delete-note", (e) => {
			if(confirm('Are you sure?')){
				this.deleteNote(e)
			}
		})
		$(document).on("click", ".edit-note", (e) => this.editNote(e))
		$(document).on("click", ".update-note", (e) => this.updateNote(e))
		$(document).on("click", ".submit-note", (e) => this.createNote(e))
	}

	// Methods
	editNote(e) {
		let thisNote = $(e.currentTarget).closest('li')
		if(thisNote.data("state")=="editable") {
			this.makeNoteReadonly(thisNote)
		}else{
			this.makeNoteEditable(thisNote)
		}
	}

	makeNoteEditable(thisNote) {
		thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"> Cancel</i>')
		thisNote.find(".note-title-field, .note-body-field").removeAttr('readonly').addClass('note-active-field')
		thisNote.find(".update-note").addClass('update-note--visible')
		thisNote.data("state", "editable")
	}

	makeNoteReadonly(thisNote) {
		thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"> Edit</i>')
		thisNote.find(".note-title-field, .note-body-field").attr('readonly', true).removeClass('note-active-field')
		thisNote.find(".update-note").removeClass('update-note--visible')
		thisNote.data("state", "")
	}


	async deleteNote(e) {
		// let post_id= el.closest('li').dataset.id
		let thisNote = $(e.currentTarget).closest('li')
		let post_id = thisNote.data('id')
		console.log(`Delete Post Id: ${post_id}`)
		const res = await axios.delete(universityData.root_url+'/wp-json/wp/v2/note/'+post_id, {
			headers: {
				'X-WP-Nonce': universityData.nonce
			}
		})
		console.log(res)
		if(res.status==200) {
			// el.closest('li').remove()
			$(e.currentTarget).closest('li').fadeOut()
			if(res.data.userNoteCount<=4) {
				$(".note-limit-message").removeClass('active')
			}
		}
	}

	async updateNote(e) {
		let thisNote = $(e.currentTarget).closest('li')
		console.log(thisNote)
		let post_id= thisNote.data('id')
		console.log(post_id)
		let ourUpdatedPost = {
			'title': thisNote.find('.note-title-field').val(),
			'content': thisNote.find('.note-body-field').val(),
		}
		console.log(`Save Post Id: ${post_id}`)
		const res = await axios.post(universityData.root_url+'/wp-json/wp/v2/note/'+post_id, ourUpdatedPost, {
			headers: {
				'X-WP-Nonce': universityData.nonce
			}
		})
		console.log(res)
		if(res.status==200) {
			this.makeNoteReadonly(thisNote)
		}
	}

	async createNote(e) {
		let ourNewPost = {
			'title': $('.new-note-title').val(),
			'content': $('.new-note-body').val(),
			'status': 'publish',
		}
		const res = await axios.post(universityData.root_url+'/wp-json/wp/v2/note/', ourNewPost, {
			headers: {
				'X-WP-Nonce': universityData.nonce
			}
		})
		console.log(res)
		if(res.data.id) {
			$(`
				<li data-id="${res.data.id}">
					<input type="text" class="note-title-field" value="${res.data.title.raw}" readonly >
					<span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"> Edit</i></span>
					<span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"> Delete</i></span>
					<textarea readonly class="note-body-field">${res.data.content.raw}</textarea>
					<span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"> Save</i></span>
				</li>
			`).prependTo("#my-notes").hide().slideDown();
			$('.new-note-title').val(null)
			$('.new-note-body').val(null)
		}else{
			$(".note-limit-message").addClass('active')
		}
	}

}

export default MyNotes;