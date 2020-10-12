<template>
    <div>
        <div class="content-wrapper">
            <h1>Избранное</h1>

            <div class="adverts-wrapper" v-if="adverts.length > 0">
                <v-row class="adverts-row">
                    <v-col cols="12" md="6" lg="4" v-for="advert in adverts" :key="advert.id">
                        <Advert :advert="advert"></Advert>
                    </v-col>
                </v-row>
            </div>

            <div class="no-data-block" v-else>
                <svg width="179" height="139" viewBox="0 0 179 139" fill="none" xmlns="http://www.w3.org/2000/svg"><g filter="url(#filter0_d)"><rect x="43" y="19.999" width="80" height="60" rx="6" fill="white"></rect><rect x="43.5" y="20.499" width="79" height="59" rx="5.5" stroke="#D5DBE5"></rect></g><rect x="51" y="59.999" width="55" height="4" rx="2" fill="#4364DA"></rect><rect x="51" y="67.999" width="40" height="4" rx="2" fill="#4364DA"></rect><rect x="51" y="27.999" width="64" height="25" rx="3" fill="#F0F0F9"></rect><g filter="url(#filter1_d)"><circle cx="38" cy="79.999" r="17.5" fill="white" stroke="#D5DBE5"></circle><path fill-rule="evenodd" clip-rule="evenodd" d="M36.6877 87.6191L38 88.9324L39.3042 87.6265C43.1914 84.185 46 81.6984 46 78.5613C46 76.0031 43.9977 73.999 41.4415 73.999C40.1665 73.999 38.9074 74.5312 38 75.4385C37.0926 74.5312 35.8335 73.999 34.5585 73.999C32.0023 73.999 30 76.0031 30 78.5613C30 81.6963 32.8047 84.1815 36.6877 87.6191Z" fill="#FF8C42"></path></g><g filter="url(#filter2_d)"><rect x="110" y="58.999" width="49" height="60" rx="6" fill="white"></rect><rect x="110.5" y="59.499" width="48" height="59" rx="5.5" stroke="#D5DBE5"></rect></g><rect x="114.9" y="98.999" width="33.6875" height="4" rx="2" fill="#4364DA"></rect><rect x="114.9" y="106.999" width="24.5" height="4" rx="2" fill="#4364DA"></rect><rect x="148.591" y="22.833" width="6" height="1.5" rx="0.75" transform="rotate(-45 148.591 22.833)" fill="#4364DA"></rect><rect x="152.833" y="23.8945" width="6" height="1.5" rx="0.75" transform="rotate(-135 152.833 23.8945)" fill="#4364DA"></rect><rect x="136.121" y="35.7773" width="8" height="2" rx="1" transform="rotate(-45 136.121 35.7773)" fill="#FF8C42"></rect><rect x="141.778" y="37.1914" width="8" height="2" rx="1" transform="rotate(-135 141.778 37.1914)" fill="#FF8C42"></rect><rect x="58.1211" y="114.778" width="8" height="2" rx="1" transform="rotate(-45 58.1211 114.778)" fill="#4364DA"></rect><rect x="63.7783" y="116.192" width="8" height="2" rx="1" transform="rotate(-135 63.7783 116.192)" fill="#4364DA"></rect><rect x="114.9" y="66.999" width="39.2" height="25" rx="3" fill="#F0F0F9"></rect><defs><filter id="filter0_d" x="23" y="-0.000976562" width="120" height="100" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood><feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix><feOffset></feOffset><feGaussianBlur stdDeviation="10"></feGaussianBlur><feColorMatrix type="matrix" values="0 0 0 0 0.390938 0 0 0 0 0.432348 0 0 0 0 0.579167 0 0 0 0.2 0"></feColorMatrix><feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"></feBlend><feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"></feBlend></filter><filter id="filter1_d" x="0" y="41.999" width="76" height="76" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood><feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix><feOffset></feOffset><feGaussianBlur stdDeviation="10"></feGaussianBlur><feColorMatrix type="matrix" values="0 0 0 0 0.390938 0 0 0 0 0.432348 0 0 0 0 0.579167 0 0 0 0.2 0"></feColorMatrix><feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"></feBlend><feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"></feBlend></filter><filter id="filter2_d" x="90" y="38.999" width="89" height="100" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood><feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix><feOffset></feOffset><feGaussianBlur stdDeviation="10"></feGaussianBlur><feColorMatrix type="matrix" values="0 0 0 0 0.390938 0 0 0 0 0.432348 0 0 0 0 0.579167 0 0 0 0.2 0"></feColorMatrix><feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"></feBlend><feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"></feBlend></filter></defs></svg>
                <p>Как только Вы добавите первое объявление в избранное,
                    Вы сможете просмотреть его здесь!
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import Advert from "./Profile/Advert";

    export default {
        components: { Advert },
        data: function() {
            return {
                user_id: window.user.id,
                user: null,
                adverts: []
            }
        },
        mounted() {
            this.getUser(this.user_id);
            this.getAdverts();
        },
        methods: {
            getUser() {
                axios.get( window.backend_url + 'api/user/' + this.user_id).then((response) => {
                    this.user = response.data;
                });
            },
            getAdverts() {
                axios.get(window.backend_url + `api/user/${this.user_id}/favorites`).then((response) => {
                    let data = response.data.data;
                    if (data.length > 0)
                        data.map((el) => {
                            this.adverts.push(el.advert);
                        });

                    this.$parent.loading = false;
                });
            },
            remove(id) {
                axios.delete(window.backend_url + `api/user/${this.user_id}/favorites/${id}`)
                    .then((response) => {
                         if (response.status === 200) {
                             // toast
                         }
                    });
            }
        },
        watch: {
            user: function(value) {
                //
            }
        }
    }
</script>

<style>
    .adverts-wrapper {
        margin-top: 2rem;
    }

    .adverts-row {
        align-items: flex-start;
        justify-content: flex-start;
    }

    .no-data-block {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 2rem;
    }

    .no-data-block p {
        width: 70%;
        text-align: center;
        margin-top: 1rem;
    }
</style>
