<template>
    <div class="container">
        <ul>
            <legend>online user :</legend>
            <li v-for="online in onlines">{{online.name}}</li>
        </ul>
        <br><br>
        <span class="label label-success" style="margin-left:15px;" v-if="post.published">Published</span>

        <span class="label label-default" style="margin-left:15px;" v-else>Draft</span>
        <br>

        <hr/>
        <p class="lead">
            {{post.content}}
        </p>

        <hr/>

        <h3>Comments:</h3>
        <span v-if="typing">{{typing.name}} is typing ...</span>
        <div style="margin-bottom:50px;" v-if="user">
            <textarea class="form-control" rows="3" name="body" placeholder="Leave a comment" v-model="commentBox"
                      @keydown="isTyping()"></textarea>
            <button class="btn btn-success" style="margin-top:10px" @click="postComment()">Save Comment</button>
        </div>
        <div v-else>
            <h4>you must logged in to submit a comment ...</h4>
            <a href="/login">Login Now &gt;&gt;</a>
        </div>

        <div class="media " style="margin-top:20px; background-color: #F3EEF1" v-for="(comment , index) in comments"
             v-if="comment.parent_id == null">
            {{typeof(comment.parent_id)}}
            <div class="media-left mx-3">

                <a href="#">
                    <img class="media-object" src="http://placeimg.com/80/80" alt="...">
                </a>
            </div>
            <div class="media-body">

                <h4 class="media-heading "> {{comment.user.name}}
                    <mark>said ...</mark>
                </h4>

                <p v-if="edit !== comment.id">
                    {{ comment.body }}
                </p>
                <div class="row" v-else>
                    <div class="col-12">
                        <textarea name="" id="" cols="100" rows="5" v-model="editComment" v-text="comment.body"></textarea>
                    </div>
                    <div class="col-12 mb-4">
                        <button class="btn  btn-primary mr-1" @click="updateComment(index , comment.id)">ok</button>
                        <button class="btn  btn-warning" @click="cancelComment()">cancel</button>

                    </div>
                </div>

                <span class="font-weight-bold" dir="rtl"> {{ comment.created_at | mydate  }}</span>
                <span class="badge badge-warning ml-1" v-if="comment.created_at !== comment.updated_at">edited</span>
                <button class="btn btn-sm btn-danger float-right" v-if="comment.user.id == user.id && edit !== comment.id"
                        @click="deleteComment(comment.id)">delete
                </button>
                <button class="btn btn-sm btn-warning float-right" v-if="comment.user.id == user.id && edit !== comment.id"
                        @click="enableUpdate(comment.id)">update
                </button>
                <button class="btn btn-success" @click="replyEnable(comment.id)">reply</button>
                <div class="media my-1" v-for="child in comment.children">
                    <div class="media-left mx-3">

                        <a href="#">
                            <img class="media-object" src="http://placeimg.com/80/80" alt="...">
                        </a>
                    </div>
                    <div class="media-body">
                        <div class="media-heading">{{child.user.name}}</div>
                        <p class="float-left">{{child.body}}</p>
                        <span class="float-right">{{child.created_at | mydate}}</span>
                    </div>

                </div>
                <div v-if="reply == comment.id">
                    <textarea name="reply" id="reply" cols="80" rows="5" v-model="replyComment"></textarea>
                    <button class="btn btn-success" @click="ReplyCm(comment.id , index) ">ok</button>

                </div>
            </div>
        </div>
        <br>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item ">
                    <a class="page-link" @click="getComments(pagination.prev_page)" tabindex="-1" v-if="pagination.prev_page">Previous</a>
                </li>

                <li class="page-item">
                    <a class="page-link" @click="getComments(pagination.next_page)" v-if="pagination.next_page">next</a>
                </li>
            </ul>
        </nav>
    </div>

</template>

<script>

	export default {
		props: ['user', 'post'],

		data() {
			return {
				comments: [],
				commentBox: '',
				onlines: {},
				typing: false,
				edit: {},
				editComment: '',
				reply: {},
				replyComment: '',
				commentID: {},
				pagination: {},
			};
		},

		mounted() {
			this.getComments();
			this.listen();
			this.listUser();
			this.listenDelete();
			this.listenUpdate();
		},
		methods: {

			ReplyCm(id, index) {
				axios.post('/reply/' + id, { body: this.replyComment })
					.then((response) => {
						this.comments[index].children.push(response.data);

					})
				;
				this.replyComment = '';
			},
			replyEnable(id) {
				this.reply = id;
			},
			enableUpdate(id) {
				this.edit = id;

			},
			updateComment(index, id) {
				this.comments[index].body = this.editComment;
				axios.patch('/posts/comments/' + id, { body: this.editComment });
				this.edit = {};
				this.listenUpdate();
			},
			cancelComment() {
				this.edit = {};
			},
			getComments(page) {
				if (page) {
					axios.get(page)
						.then((response) => {
							this.comments   = response.data.data.data;
							this.pagination = response.data.pagination;
						})
						.catch(function (error) {
							console.log(error);
						});
				} else {
					axios.get('/api/posts/' + this.post.id + '/comments')
						.then((response) => {
							this.comments   = response.data.data.data;
							this.pagination = response.data.pagination;
                            console.log(response.data.pagination.next_page);
						})
						.catch(function (error) {
							console.log(error);
						});
				}
			},
			deleteComment(id) {
				this.commentID = id;

				axios.delete('/posts/comment/' + id, { data: { id: 'id' } })
					.then(
						this.comments = this.comments.filter(u => (u.id !== id)),
					);
				this.listenDelete();
			},

			postComment() {

				axios.post('/posts/' + this.post.id + '/comments', { body: this.commentBox })
					.then((response) => {
						this.comments.unshift(response.data);
						this.commentBox = '';

					})
					.catch((error) => {
						console.log(error);
					});
			},
			listen() {
				Echo.private('post.' + this.post.id)
					.listen('NewComment', (res) => {
						if (res.parent_id) {
							this.comments.find((comment) => {
								if (comment.id == res.parent_id) {
									comment.children.push(res);
								}
							});
						} else {
							this.comments.unshift(res);
							this.getComments();
						}
					});
			},
			listenDelete() {
				Echo.private('comment')
					.listen('DeleteComment', (res) => {
						console.log('delete cm');
						console.log(res);
						if (res.parent_id) {
							this.comments.find((comment) => {
								if (comment.id == res.parent_id) {
									comment.children.filter((u) => (u.id !== res.id));
								}
							});
							console.log('delete child');
						} else {
							this.comments.filter((u) => (u.id !== res.id));
							this.getComments();
						}

					});
			},
			listenUpdate() {
				Echo.private('update')
					.listen('UpdateComment', (res) => {
						console.log('update cm');
						console.log(res);

						this.comments.find((comment) => {
							if (comment.id == res.id) {
								comment.body = res.body;
							}
						});

					});
			},

			listUser() {
				Echo.join('online')
					.here(users => (this.onlines = users))
					.joining(user => this.onlines.push(user))
					.leaving(user => (this.onlines = this.onlines.filter(u => (u.id !== user.id))))
					.listenForWhisper('typing', response => {
						console.log('is type');
						this.typing = response;
						setTimeout(() => {
							this.typing = false;
						}, 2000);
					});
			},
			isTyping() {
				Echo.join('online')
					.whisper('typing', this.user);
			},

		},
	};
</script>
