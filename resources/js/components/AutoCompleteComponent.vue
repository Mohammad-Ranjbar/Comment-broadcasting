<template>

    <div>
        <input type="text" placeholder="what are you looking for?" v-model="query" v-on:keyup="autoComplete" class="form-control">

        <transition name="fade">
            <div class="panel-footer" v-if="results.length">
                <ul class="list-group">
                    <li class="list-group-item" v-for="result in results" name="fade">
                        {{ result.name }}
                    </li>
                </ul>
            </div>
        </transition>
    </div>

</template>

<script>
	export default {
		name: 'AutoCompleteComponent',
		data() {
			return {
				query: '',
				results: [],
			};
		},
		methods: {
			autoComplete() {
				this.results = [];
				if (this.query.length > 1) {
					axios.get('/users/search', { params: { query: this.query } }).then(response => {
						this.results = response.data;
					});
				}
			},
		},
	};
</script>

<style scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }

</style>
