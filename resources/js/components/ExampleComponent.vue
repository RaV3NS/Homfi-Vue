<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body" v-on:click="count++">
                        I'm an example component. Clicks: {{ count }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                count: 0
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
