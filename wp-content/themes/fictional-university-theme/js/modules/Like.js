import $ from 'jquery'
import axios from 'axios'

class Like {
	constructor(){ 
		this.events()
	}
	
	events() {
		$(document).on("click", ".like-box", (e)=>{
			this.likeDispatcher(e)
		})
	}

	// methods
	likeDispatcher(e) {
		let thisLikeBtn = $(e.currentTarget)
		if(thisLikeBtn.attr('data-exists')=='yes'){
			this.deleteLike(thisLikeBtn)
		}else{
			this.createLike(thisLikeBtn)
		}
	}

	async createLike(thisLikeBtn) {
		console.log('create')
		let professorId = thisLikeBtn.attr('data-professor')
		const res = await axios.post(universityData.root_url+'/wp-json/university/v1/like', {professorId}, {
			headers: {
				'X-WP-Nonce': universityData.nonce
			}
		})
		// console.log(res)
		if(res.data.success) {
			thisLikeBtn.attr('data-like', res.data.likeId)
			thisLikeBtn.attr('data-exists', 'yes')
			let likeCount = thisLikeBtn.find('.like-count').html()*1
			likeCount++
			thisLikeBtn.find('.like-count').html(likeCount)
		}else{
			console.log(res.data)
		}
	}

	async deleteLike(thisLikeBtn) {
		console.log('delete')
		let likeId = thisLikeBtn.attr('data-like')
		thisLikeBtn.attr('data-exists', '')
		// console.log(likeId)
		const res = await axios.delete(universityData.root_url+'/wp-json/university/v1/like', {
			headers: {
				'X-WP-Nonce': universityData.nonce
			},
			data: {
				likeId
			}
		})
		thisLikeBtn.attr('data-like', '')
		thisLikeBtn.attr('data-exists', '')
		if(res.data.success) {
			thisLikeBtn.attr('data-like', '')
			thisLikeBtn.attr('data-exists', '')
			let likeCount = thisLikeBtn.find('.like-count').html()*1
			likeCount--
			thisLikeBtn.find('.like-count').html(likeCount)
		}else{
			console.log(res.data)
		}
	}
}

export default Like;