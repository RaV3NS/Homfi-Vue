<template>
    <div class="advert-card flex" @mouseenter="hoverMarker" @mouseleave="unHover">
        <div class="left-card">
            <carousel :autoplay="false" :data="carouselData" indicator-type="disc"></carousel>
            <button type="button" class="btn-favourites" @click="favourites">
                    <svg v-if="!isFavourite" viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g filter="url(#filter0_d)"><path d="M11.9029 19.0207L11.8813 18.9991L11.8584 18.9788C9.16904 16.5979 6.93836 14.6188 5.36799 12.7092C3.80984 10.8144 3 9.10239 3 7.27308C3 4.30715 5.30623 2 8.26799 2C9.74942 2 11.2282 2.62176 12.2929 3.68646L13 4.39352L13.7071 3.68646C14.7718 2.62176 16.2506 2 17.732 2C20.6938 2 23 4.30715 23 7.27308C23 9.10361 22.189 10.8168 20.6288 12.7131C19.0563 14.6244 16.8228 16.6053 14.1304 18.989L14.1074 19.0094L14.0857 19.0311L12.9998 20.1184L11.9029 19.0207Z" stroke="white" stroke-width="2"></path></g><defs><filter id="filter0_d" x="0" y="0" width="26" height="24.5333" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood><feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix><feOffset dy="1"></feOffset><feGaussianBlur stdDeviation="1"></feGaussianBlur><feColorMatrix type="matrix" values="0 0 0 0 0.416476 0 0 0 0 0.484595 0 0 0 0 0.620833 0 0 0 0.5 0"></feColorMatrix><feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"></feBlend><feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"></feBlend></filter></defs></svg>
                    <svg v-else viewBox="0 0 22 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.19558 18.7276L11 20.5333L12.7933 18.7378C18.1382 14.0057 22 10.5867 22 6.27308C22 2.75565 19.2469 0 15.732 0C13.9789 0 12.2477 0.731706 11 1.97934C9.75231 0.731706 8.02106 0 6.26799 0C2.75315 0 0 2.75565 0 6.27308C0 10.5838 3.85644 14.0008 9.19558 18.7276Z" fill="#FF8C42"></path></svg>
                </button>
        </div>
        <div class="right-card">
            <a class="title-link" target="_blank" :href="'/' + $store.state.city.translit + '/' + advert.id">
                <h1 class="advert-card-title">{{ advert.title.ru }}</h1>
            </a>
            <div class="price-box">
                <span class="price">{{ advert.price_month }}</span>
                <span class="currency">грн/мес</span>
            </div>
            <div class="footer-space">
                {{ getArea(advert) }}
            </div>
            <div class="flex-footer">
                <div class="footer-floors">{{ getFloor(advert) }}</div>
                <div class="footer-date">{{ getDate(advert) }}</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            carouselData: [],
            favouritesList: []
        }
    },
    props: ['advert'],
    mounted() {
        if (this.advert.images.length > 0) {
            let images = this.advert.images;
            images.map((el) => {
                var img = document.createElement('img');
                img.src = el.card;

                img.onload = () => {
                    this.carouselData.push(`<div class="slide" style="background-image: url(${el.card})"></div>`);
                };

                img.onerror = (e) => {
                    this.carouselData.push(`<div class="slide" style="background-image: url('/images/blank.png')"></div>`);
                };

            });
        } else {
            this.carouselData.push(`<div class="slide" style="background-image: url('/images/blank.png')"></div>`);
        }

        this.favouritesList = this.$store.state.favourites;
    },
    computed: {
        isFavourite: function() {
            if (this.advert)
                return this.favouritesList.includes(this.advert.id);
        }
    },
    methods: {
        favourites() {
            if (this.isFavourite) {
                axios.delete(window.backend_url + `api/user/${window.user.id}/favorites/${this.advert.id}`).then((response) => {
                    if (response.status === 200) {
                        let favourites = this.$store.state.favourites;
                        favourites.splice(this.favouritesList.indexOf(this.advert.id), 1);
                        this.$store.dispatch("setFavourites", favourites);
                        
                        this.$toast.warning( 'Объявление ' + this.advert.id + ' удалено из избранного' , {
                            position: 'top-right',
                            duration: 3000,
                            dismissible: true
                        });
                    }
                });
            } else {
                axios.post(window.backend_url + `api/user/${window.user.id}/favorites`, { advert_id: this.advert.id }).then((response) => {
                    if (response.status === 200) {
                        this.$toast.info( 'Объявление ' + this.advert.id + ' добавлено в избранное' , {
                            position: 'top-right',
                            duration: 3000,
                            dismissible: true
                        });

                        let favourites = this.$store.state.favourites;
                        favourites.push(this.advert.id);
                        this.$store.dispatch("setFavourites", favourites);
                    };   
                });
            }
        },
        hoverMarker() {
            this.$parent.$refs.map.hover_coords = [parseFloat(this.advert.lat), parseFloat(this.advert.lng)];
            this.$parent.$refs.map.hover = true;
        },
        unHover() {
            this.$parent.$refs.map.hover = false;
        },
        getAdvertParameter(advert, key) {
            let obj = advert.parameters.filter(obj => {
                return obj.key === key
            });

            if (obj.length > 0) return obj[0];
            else return null;
        },
        getArea: function (advert) {
            let totalArea = this.getAdvertParameter(advert, 'total_space');
            let livingArea = this.getAdvertParameter(advert, 'living_space');
            let kitchenArea = this.getAdvertParameter(advert, 'kitchen_space');

            let kitchenAreaLabel = kitchenArea && kitchenArea.value? ' / ' + kitchenArea.value.key + ' ' + kitchenArea.unit.ru : '';
            let totalAreaLabel = totalArea && totalArea.value ? totalArea.value.key  + ' ' + totalArea.unit.ru : '';
            let livingAreaLabel = livingArea && livingArea.value ? ' / ' + livingArea.value.key  + ' ' + livingArea.unit.ru : '';

            return totalAreaLabel + livingAreaLabel + kitchenAreaLabel;
        },
        getDate: function (advert) {
            let date = new Date(advert.publish_date);
            return this.$moment(date).format("DD.MM.YYYY");

        },
        getFloor: function (advert) {
            let floor = this.getAdvertParameter(advert, 'floor');
            let total_floor = this.getAdvertParameter(advert, 'total_floors');
            let floorLabel = floor ? floor.value.key + ' этаж' : '';
            if (total_floor && total_floor.value)
                floorLabel += ' из ' + total_floor.value.key;

            return floorLabel;
        }
    }
}
</script>

<style lang="scss" scoped>
    .left-card {
        position: relative;
    }

    .btn-favourites {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 36px;
        height: 36px;
        display: -webkit-inline-flex;
        display: -moz-inline-box;
        display: inline-flex;
        -webkit-justify-content: center;
        -moz-box-pack: center;
        justify-content: center;
        -webkit-align-items: center;
        -moz-box-align: center;
        align-items: center;
        margin: 0;
        padding: 0;
        border: none;
        border-radius: 50%;
        background: none;
        outline: none;
        cursor: pointer;
    }

    .btn-favourites:hover {
        background: hsla(0,0%,100%,.5);
        -webkit-filter: drop-shadow(0 0 20px rgba(100,110,148,.2));
        filter: drop-shadow(0 0 20px rgba(100,110,148,.2))
    }

    .btn-favourites svg {
        width: 22px;
        height: 21px;
    }

    a {
        text-decoration: none;
    }

    .flex {
        display: flex;
    }

    .flex-footer {
        margin-top: 0.5rem;
        display: flex;
        justify-content: space-between;
    }

    .footer-date {
        font-weight: 300;
        color: var(--greyish-blue);
        font-size: 14px;
        line-height: 18px;
    }

    .footer-space {
        font-size: 14px;
        line-height: 22px;
        color: var(--blue-900);
        font-weight: 400;
    }

    .price-box .price {
        padding-right: 6px;
        font-size: 28px;
        line-height: 30px;
        font-weight: 400;
    }

    .right-card {
        padding: 1rem;
        width: 100%;
    }

    .title-link:hover {
        text-decoration: none;
    }

    .advert-card-title {
        line-height: 22px;
        margin-bottom: 10px;
        color: var(--card-titel);
        font-weight: 400;
    }

    .price-box {
        margin-bottom: 10px;
        color: var(--blue-900);
    }

    .footer-floors {
        font-size: 14px;
        line-height: 22px;
        color: var(--blue-900);
        font-weight: 400;
    }

    .more-info {
        font-size: 14px;
        line-height: 18px;
    }

    .advert-card {
        border: 1px solid transparent;
        border-radius: 6px;
        height: fit-content;
    }

    .advert-card:hover {
        box-shadow: 0 0 20px rgba(100,110,148,.2);
        border: 1px solid var(--gray-300);
    }


    @media only screen and (min-width: 1280px) {
        .flex {
            flex-direction: row;
        }
    }

</style>
