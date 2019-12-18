<template>
    <div class="container">
        <ul>
            <legend>online user :</legend>
            <li v-for="online in onlines">{{online.name}}</li>
        </ul>
        <br><br>
        <span class="label label-success" style="margin-left:15px;" v-if="post.published">Published</span>

        <span class="label label-default" style="margin-left:15px;" v-else>Draft</span>

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

        <div class="media " style="margin-top:20px; background-color: #F3EEF1" v-for="(comment , index) in comments">
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
                <div v-else>
                    <textarea name="" id="" cols="60" rows="5" v-model="editComment" v-text="comment.body"></textarea>
                    <button class="btn  btn-primary" @click="updateComment(index , comment.id)">ok</button>
                </div>

                <span style="color: #AAA;" dir="rtl"> {{ comment.created_at | mydate  }}</span>
                <button class="btn btn-sm btn-danger float-right" v-if="comment.user.id == user.id"
                        @click="deleteComment(comment.id)">delete
                </button>
                <button class="btn btn-sm btn-warning float-right" v-if="comment.user.id == user.id "
                        @click="enableUpdate(comment.id)">update
                </button>
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
                editComment:''
			};
		},

		mounted() {
			this.getComments();
			this.listen();
			this.listUser();
		},
		methods: {
			enableUpdate(id) {
				this.edit = id;
			},
			updateComment(index, id) {

				axios.patch('/posts/comments/' + id, {  body: this.editComment})
					.then(response => {
								this.comments[index] = response.data;
						},
					);
				this.edit = {};
				this.getComments();

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
			deleteComment(name, id) {
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
					.listen('NewComment', (comment) => {
						this.comments.unshift(comment);
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
