@extends('layouts.app')

@section('content')
    <div class="container" id="article">
        <h1>{{ $post->title }}</h1>
        {{ $post->updated_at->toFormattedDateString() }}
        @if ($post->published)
            <span class="label label-success" style="margin-left:15px;">Published</span>
        @else
            <span class="label label-default" style="margin-left:15px;">Draft</span>
        @endif
        <hr/>
        <p class="lead">
            {{ $post->content }}
        </p>
        <hr/>

        <h3>Comments:</h3>
        <div style="margin-bottom:50px;" >
            <textarea class="form-control" rows="3" name="body" placeholder="Leave a comment" v-model="commentBox"></textarea>
            <button class="btn btn-success" style="margin-top:10px" @click="postComment()">Save Comment</button>
        </div>

        <div class="media" style="margin-top:20px;" v-for="comment in comments">
            <div class="media-left mx-3">
                <a href="#">
                    <img class="media-object" src="http://placeimg.com/80/80" alt="...">
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-heading">@{{ user.name }} said...</h4>
                <p>
                    @{{ comment.body }}
                </p>
                <span style="color: #aaa;">on @{{ comment.created_at }}</span>
                <span>{{auth()->user()->createToken('MyApp')->accessToken}} </span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.js"></script>

    <script>
		const app = new Vue({
			el: '#article',
			data: {
				comments: {},
				commentBox: '',
				post: {!! $post->toJson() !!},
				user: {!! Auth::check() ? Auth::user()->toJson() : 'null' !!},
			},
			mounted() {
				this.getComments();

			},
			methods: {
				getComments() {
					axios.get('/api/posts/' + this.post.id + '/comments')
						.then((response) => {
							this.comments = response.data;

						})
						.catch(function (error) {
							console.log(error);
						});
				},
				postComment() {
					axios.post('/api/posts/' + this.post.id + '/comments', { body: this.commentBox }, {
						headers:
							{
								'Authorization': 'Bearer ' + this.tokenize,
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
		});

    </script>
@endsection
