<template>
    <div class="container">

        <span class="label label-success" style="margin-left:15px;" v-if="post.published">Published</span>

        <span class="label label-default" style="margin-left:15px;" v-else>Draft</span>

        <hr/>
        <p class="lead">
            {{post.content}}
        </p>
        <hr/>

        <h3>Comments:</h3>
        <div style="margin-bottom:50px;">
            <textarea class="form-control" rows="3" name="body" placeholder="Leave a comment" v-model="commentBox"></textarea>
            <button class="btn btn-success" style="margin-top:10px" @click="postComment()">Save Comment</button>
        </div>

        <div class="media " style="margin-top:20px; background-color: #D6FFDC" v-for="comment in comments">
            <div class="media-left mx-3">

                <a href="#">
                    <img class="media-object" src="http://placeimg.com/80/80" alt="...">
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-heading "> {{comment.user.name}} said ... </h4>
                <p>
                    {{ comment.body }}
                </p>
<!--                <span style="color: #AAA;">on @{{ comment.created_at }}</span>-->
                <span style="color: #AAA;">on @{{ moment(comment.created_at).fromNow() }}</span>

            </div>
        </div>
    </div>
</template>

<script>
	var moment = require('moment');
	export default {

		props: ['user', 'post'],
		data() {
			return {
				comments: {},
				commentBox: '',
				moment: moment
			};
		},
		created() {
			this.getComments();

		},
		methods: {

			getComments() {

				axios.get('/api/posts/' + this.post.id + '/comments')
					.then((response) => {
						this.comments = response.data;
						console.log( response.data.user);
					})
					.catch(function (error) {
						console.log(error);
					});
			},
			postComment() {

				axios.post('/posts/' + this.post.id + '/comments', { body: this.commentBox }, {
					headers:
						{
							'Authorization': 'Bearer ' + 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjdmNmQzNTQ1Y2JlYjQ4YjIzODcyMGIxNDM4YTBjYjNkM2JjYTFhYjJjYzVlNjlmZmE2OTMxYTU0OWUxY2IxYzA1ZTFmMWMyNzYxZGU0NzIzIn0.eyJhdWQiOiIxIiwianRpIjoiN2Y2ZDM1NDVjYmViNDhiMjM4NzIwYjE0MzhhMGNiM2QzYmNhMWFiMmNjNWU2OWZmYTY5MzFhNTQ5ZTFjYjFjMDVlMWYxYzI3NjFkZTQ3MjMiLCJpYXQiOjE1NzU5NTQwNjgsIm5iZiI6MTU3NTk1NDA2OCwiZXhwIjoxNjA3NTc2NDY4LCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.aZ0M07zHTJZhuz4igUrqK7T6FCnVcljbtlRE__SS1vhmyXUjvimmqxRV27LDujy-YnHsKfdb49DRO4YRZSJtzyI47geMD1k1FiTl0WA4Tw2iEnEsorZyMvyRd9btAiJkoiI6PjFg4iqSuVhVcnmcPXZrdxCBJlouIyxSUJEmt9w_zNxfR0ZMp8U3mdyf49xLBP6p2SZbKUxA5uenW_Wa55yV7pfmbGKJNLKLqn2oT6y6-8Kzcnig1lRpav6glQxnq6Wk8BTXkzXjoaOKkBEFB7YE1J66Kons7VGmKXsgKcJT6B9TnHxOfjIVT1OcHVkZ9SRKErN8jIfZZDe9Z_wZcVog6_NZku-CsXjsquRv2VQkzK6mOcpkzDrmjsY8H11O4UcG73OxunOxjtPeV5XT-L0xu4lfyAXpGAVVu8fGetj3nttxz_2BNT1Ke7wvB0O0L-FTvZhc_c9pn3HHRYvPzlnOqP-FbDscyMXyk4iVA_WVIdKH_KNV7pinSQ5iC-95dUPbTuLXaLPHOrsWpqxFEL0tgIqqR8Y09K4bhaUO52bDK_-Xwvz2Y94kwtdHt9BeBEoqUffhMs5zHevabsIepq7iwPS9tBe5Hl5-Yadrvi8Dk-L1SNDbAECEE9P6YZYRTxA3o1kJNV3CUm7kV5MFoOd8FfvmlsmJTRDal2hG5gs',
						},
				})
					.then((response) => {
						this.comments.unshift(response.data);
						this.commentBox = '';
					})
					.catch((error) => {
						console.log(error);
					});
			},
		},
	};
</script>
