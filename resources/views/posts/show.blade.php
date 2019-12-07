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
        <div style="margin-bottom:50px;" v-if="user">
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
				user: {!! Auth::check() ? Auth::user()->toJson() : 'null' !!}
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
								'Authorization': 'Bearer ' + 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjJkNTJhMzdlOWE5OTZhNTAxMDg4MGFlMWExMDkzNTAwYzQ3ZTE1YTY4NGY2YzFiMmNiYzBlNjQ5ODZmNTFlYTMzNzhiNjcwY2JiZGIzYTg1In0.eyJhdWQiOiIxIiwianRpIjoiMmQ1MmEzN2U5YTk5NmE1MDEwODgwYWUxYTEwOTM1MDBjNDdlMTVhNjg0ZjZjMWIyY2JjMGU2NDk4NmY1MWVhMzM3OGI2NzBjYmJkYjNhODUiLCJpYXQiOjE1NzU3MDI2MTYsIm5iZiI6MTU3NTcwMjYxNiwiZXhwIjoxNjA3MzI1MDE2LCJzdWIiOiIzIiwic2NvcGVzIjpbXX0.IPuJ1n5XNbbh1Czcn4wmQT9upTs_ZaZIX-wM6TV0qffCxL5m4J3ssZAk5wVmU_a6AwHmVlv3xSUt2vC3yPVCFTVIF_V6hJm7xJHcHrsEgkwkHtlyrVP97Icqj18jh0SqOZrixvGsgKezZUakhrYg_LLhrb1j8cPzm-rijlL0Rqtv3Rnw6op9tBqwHA-fMRQBLfp5pEx7ufdGbNuwQwkKVU3erKXNZR0VMxjG36LRU0PC_7AxXetWlnoJ2NhKBLAGU2zPh1fwzM8QaZJ2wVDBV0uu07GcYE55MGM-P_XiM-Tm82XBWuzssaNejPC3uYIXJ6oz22eWF9m9NKjYNyvLR0lswhJ_Dq-xBszvhvjEGJYl2ILM9vFUqcA-HjooaS22XviL2IHO1lCbwqEPK9TKOJuIb7x1pNec4eFYSrdQUGTpyYfSO3mo4of76ZOuQ1J0tF7MV-_BPsfvozFBzL89UDtyQg8i-AfQ-c2j1BWUYvceDzEr4_IH5AhcCK3ik_hjHJQiFXDhEgmn7DGnHJe-UWQoC_vVIXRDnFBf-G33NnaeMnPSVBVfCpIQU4v_ihqBO0W3bSTmuoIei2spThX6eQFmM1S8JfD4czk-uh4SV8Qdqxp9SW1ogl4SuA5R2rMr7IX8_8kEjuquY3UpzDXh9LmZuJkrjBVq5BpY17RjT3M',
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
