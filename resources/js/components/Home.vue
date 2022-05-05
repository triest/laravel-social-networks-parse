<template>
  <div>
    <p><input type="text" name="link" v-model="link" @change="sendLink">

    </p>
    <p>
      <div class="row">
        <div class="col-sm">
          <img v-if="results && results.avatar" :src="results.avatar" v-bind:alt="pic">
        </div>

        <div class="col-sm">
          <b> Followers</b>
    <p> <span
        v-if="results && results.followers_count">{{ results.followers_count }} </span>
    </p>
  </div>
       <div class="col-sm">
    <b>Posts</b>
    <p>  <span
        v-if="results && results.common_count!=null">{{ results.common_count }} </span></p>
  </div>
  </div>
  </div>
</template>
<style>
* {
    box-sizing: border-box;
    padding: 0;
    margin: 0;
}

h1, h2, h3, h4, h5, h6 {
    color: #fff;
}
</style>
<script>
export default {
    data() {
        return {
            link: null,
            avatar: null,
            results: null
        }
    },
    created() {

    },

    methods: {
        sendLink() {
            this.axios
                .post('/api/parse', {'link': this.link})
                .then(response => (
                    this.results = response.data
                ))
                .catch(function (error) {

                    if (error.response) {
                        if (error.response.status === 422) {
                            alert(error.response.data.message);
                        }

                    }
                })
        }
    },


}
</script>
