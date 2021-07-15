// import $ from 'jquery';
import axios from 'axios';

class Search {
	// 1. describe and create/initiate our object
	constructor() {
		this.addSearchHTML();
		this.openButton = document.querySelectorAll(".js-search-trigger");
		this.closeButton = document.querySelector(".search-overlay__close");
		this.searchOverlay = document.querySelector(".search-overlay");
		this.searchInput = document.querySelector("#search-term");
		this.resultDiv = document.querySelector("#search-overlay__results");
		this.events();
		this.isOverlayOpen = false;
		this.isLoading = false;
		this.typingTimer;
	}

	// 2. events
	events() {
		this.openButton.forEach((btn) => {
			btn.addEventListener("click", (e) => {
				e.preventDefault()
				this.openOverlay()
			});
		});
		this.closeButton.addEventListener("click", () => this.closeOverlay());
		document.addEventListener("keyup", (e) => this.keyPressDispatcher(e));
		this.searchInput.addEventListener("keyup", (e)=>this.typingLogic(e));
	}

	// 3. methods (function, action...)
	openOverlay() {
		this.resultDiv.innerHTML = null;
		this.searchOverlay.classList.add("search-overlay--active");
		document.querySelector("body").classList.add("body-no-scroll");
		this.isOverlayOpen = true;
		setTimeout(() => this.searchInput.focus(), 401);
		
	}

	closeOverlay() {
		this.searchOverlay.classList.remove("search-overlay--active");
		document.querySelector("body").classList.remove("body-no-scroll");
		this.isOverlayOpen = false;
		this.searchInput.value = null;
	}

	keyPressDispatcher(e) {
		let inputActive = false;
		document.querySelectorAll('input, textarea').forEach(function(inp) {
			if(inp === document.activeElement){
				inputActive = true;
			}
		});
		if(e.keyCode == 83 && !this.isOverlayOpen && !inputActive) {
			this.openOverlay()
		}
		if(e.keyCode == 27 && this.isOverlayOpen) {
			this.closeOverlay()
		}
	}

	typingLogic(e) {
		clearTimeout(this.typingTimer);
		this.showSpinner();
		let search = e.currentTarget.value
		this.typingTimer = setTimeout(()=>{
			this.hideSpinner();
			this.getResults(search);
		}, 300);
	}

	async getResults(search) {
		if(!search){
			this.hideSpinner()
			return;
		}
		// when then ทำงานพร้อมกัน ถ้าเสร็จทั้งหมดแล้ว ค่อยไปต่อ
		// $.when(
		// 	axios.get(universityData.root_url+'/wp-json/wp/v2/posts?search='+search), 
		// 	axios.get(universityData.root_url+'/wp-json/wp/v2/pages?search='+search)
		// ).then((res1, res2) => {
		// 		let result = res1.data.concat(res2.data)
		// 		this.resultDiv.innerHTML = `
		// 			<h2 class="search-overlay__section-title">General Information</h2>
		// 				${ result.length 
		// 					? 
		// 						`<ul class="link-list min-list">
		// 							${result.map((item)=>{return `<li><a href="${item.link}">${item.title.rendered}</a> ${item.type=='post' ? `by ${item.authorName}`:''}</li>`}).join('')}
		// 						</ul>
		// 						`
		// 					: 
		// 						`<p>No General Information matches that search.</p>`
		// 				}
		// 		`;
		// 	}
		// );

		const res = await axios.get(universityData.root_url+'/wp-json/university/v1/search?term='+search);
		const result = res.data;
		// console.log(result)
		this.resultDiv.innerHTML = `
			<div class="row">
				<div class="one-third">
					<h2 class="search-overlay__section-title">General Information</h2>
					${ result.generalInfo.length 
						? 
							`<ul class="link-list min-list">
								${result.generalInfo.map((item)=>{return `<li><a href="${item.permalink}">${item.title}</a> ${item.postType=='post' ? `by ${item.authorName}`:''}</li>`}).join('')}
							</ul>
							`
						: 
							`<p>No General Information matches that search.</p>`
					}
				</div>
				<div class="one-third">
					<h2>Programs</h2>
					${ result.programs.length 
						? 
							`<ul class="link-list min-list">
								${result.programs.map((item)=>{return `<li><a href="${item.permalink}">${item.title}</a></li>`}).join('')}
							</ul>
							`
						: 
							`<p>No Program matches that search. <a href="${universityData.root_url}/programs">View all programs</a></p>`
					}
					<h2>Professors</h2>
					${ result.professors.length 
						? 
							`<ul class="professor-cards" >
								${result.professors.map((item)=>{return `
								<li class="professor-card__list-item">
									<a href="${item.permalink}"  class="professor-card">
										<img src="${item.thumbnail}" class="professor-card__image">
										<span class="professor-card__name">${item.title}</span>
									</a>
								</li>
							`}).join('')}
							</ul>
							`
						: 
							`<p>No Professors matches that search.</p>`
					}
				</div>
				<div class="one-third">
					<h2>Campuses</h2>
					${ result.campuses.length 
						? 
							`<ul class="link-list min-list">
								${result.campuses.map((item)=>{return `<li><a href="${item.permalink}">${item.title}</a></li>`}).join('')}
							</ul>
							`
						: 
							`<p>No Campuses matches that search. <a href="${universityData.root_url}/campuses">View all campuses</a></p>`
					}
					<h2>Events</h2>
					${ result.events.length 
						? 
							`
								${result.events.map((item)=>{return `
								
									<div class="event-summary">
										<a class="event-summary__date t-center" href="${item.permalink}">
											<span class="event-summary__month">${item.month}</span>
											<span class="event-summary__day">${item.day}</span>
										</a>
										<div class="event-summary__content">
											<h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
											<p>${item.description} <a href="${item.permalink}" class="nu gray">Learn more</a></p>
										</div>
									</div>											
								`}).join('')}
							`
						: 
							`<p>No Events matches that search.</p>`
					}
				</div>
			</div>
		`;

	}

	showSpinner() {
		if(!this.isLoading){
			this.resultDiv.innerHTML =  '<div class="spinner-loader"></div>';
			this.isLoading = true;
		}
	}

	hideSpinner() {
		if(this.isLoading){
			this.resultDiv.innerHTML =  '';
			this.isLoading = false;
		}
	}

	addSearchHTML() {
		document.querySelector("footer").insertAdjacentHTML('afterend', `
			<div class="search-overlay">
				<div class="search-overlay__top">
					<div class="container">
						<i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
						<input type="text" class="search-term" placeholder="What are you looking for?" id="search-term" autocomplete="off">
						<i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
					</div>
				</div>
				<div class="container">
					<div id="search-overlay__results"></div>
				</div>
			</div>
		`);
	}


}

export default Search;