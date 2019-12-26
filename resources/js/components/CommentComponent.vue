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

                <h4 class="media-heading "> {{comment.user.name}} said ... </h4>

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
			};
		},

		mounted() {
			this.getComments();
			this.listen();
			this.listUser();

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
			},
			cancelComment() {
				this.edit = {};
			},
			getComments() {
				axios.get('/api/posts/' + this.post.id + '/comments')
					.then((response) => {
						this.comments = response.data;
					})
					.catch(function (error) {
						console.log(error);
					});

			},
			deleteComment(id) {
				axios.delete('/posts/comment/' + id, { data: { id: 'id' } })
					.then(
						this.comments = this.comments.filter(u => (u.id !== id)),
					);

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
								if(comment.id == res.parent_id){
									comment.children.push(res);
                                }
                            });
							console.log('this is child');
						} else {
							this.comments.unshift(res);
                            this.getComments();
						}

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
