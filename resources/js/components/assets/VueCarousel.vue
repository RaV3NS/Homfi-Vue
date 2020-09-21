<template>
    <div>
        <vue-flux
            :options="vfOptions"
            :images="vfImages"
            :transitions="vfTransitions"
            :captions="vfCaptions"
            ref="slider">

            <template v-slot:preloader>
                <flux-preloader />
            </template>

            <template v-slot:caption>
                <flux-caption />
            </template>

            <template v-slot:pagination>
                <flux-pagination />
            </template>

        </vue-flux>

<!--  <button @click="$refs.slider.show('next')">NEXT</button>  -->
    </div>
</template>

<script>
    import {
        VueFlux,
        FluxCaption,
        FluxControls,
        FluxIndex,
        FluxPagination,
        FluxPreloader
    } from 'vue-flux';

    export default {
        components: {
            VueFlux,
            FluxCaption,
            FluxControls,
            FluxIndex,
            FluxPagination,
            FluxPreloader,
        },

        data: () => ({
            vfOptions: {
                autoplay: false
            },
            vfImages: ['https://np.pl.ua/wp-content/uploads/2020/06/google_logo_1.jpg', 'https://np.pl.ua/wp-content/uploads/2020/06/google_logo_1.jpg'],
            vfCaptions: [],
            vfTransitions: ['fade']
        }),

        props: ["images"],

        watch: {
            images: function(newVal, oldVal) { // watch it
                console.log('Prop changed: ', newVal, ' | was: ', oldVal)
            }
        },

        mounted() {
            if (this.images && this.images.length > 0)
                this.images.map((img) => {
                    this.vfImages.push(img.card);
                })
        }
    }
</script>
