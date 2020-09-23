<template>
    <div class="advert-card flex" @mouseenter="hoverMarker" @mouseleave="unHover">
        <div class="left-card">
            <carousel :autoplay="false" :data="carouselData" indicator-type="disc"></carousel>
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
            carouselData: []
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
    },
    methods: {
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
            return ('0' + date.getDate()).slice(-2) + '.' + ('0' + (date.getMonth() + 1)).slice(-2) + '.' + date.getFullYear();

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




</style>
