<template>
    <div class="photo-wrapper">
        <img :src="src" :style="`transform: rotate(${rotation}deg)`">
        <button class="close"><img alt="remove" src="/icons/remove.svg" @click="remove"></button>
        <button class="rotation"><img alt="rotate" src="/icons/rotate.svg" @click="rotate"></button>
        <button class="main" v-if="id === 0">Главное фото</button>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                rotation: 0
            }
        },
        props: ['src', 'id'],
        methods: {
            remove() {
                this.$emit('remove', { id: this.id, isRequest: !!this.src });
            },
            rotate() {
                this.rotation += 90;
                this.$emit('rotate', { id: this.id, rotation: this.rotation });
            }
        },
        watch: {
            rotation: function(value) {
                if (value > 270) this.rotation = 0;
            }
        }
    }
</script>

<style lang="scss" scoped>
    button.main {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 14px;
        line-height: 22px;
        font-weight: 400;
        color: var(--blue-900);
        padding: 7px 10px;
        background: var(--white);
        border: 1px solid var(--gray-300);
        border-radius: 6px;
        z-index: 1;
        outline: none;
    }

    img {
        border-radius: 5px;
        width: 100%;
        height: 100%;
    }

    .photo-wrapper {
        width: 190px;
        height: 150px;
        margin: 10px;
        position: relative;
        overflow-y: hidden;
    }

    button.close {
        top: 10px;
        width: 40px;
        height: 40px;
        padding: 0;
        margin: 0;
        background: var(--white);
        border: 1px solid var(--gray-300);
        border-radius: 50%;
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -webkit-justify-content: center;
        -moz-box-pack: center;
        justify-content: center;
        -webkit-align-items: center;
        -moz-box-align: center;
        align-items: center;
        cursor: pointer;
        z-index: 1;
        outline: none;
        left: 10px;
        position: absolute;
    }

    button.close img {
        width: 25px;
    }

    button.rotation {
        left: 53px;
        position: absolute;
        top: 10px;
        width: 40px;
        height: 40px;
        padding: 0;
        margin: 0;
        background: var(--white);
        border: 1px solid var(--gray-300);
        border-radius: 50%;
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -webkit-justify-content: center;
        -moz-box-pack: center;
        justify-content: center;
        -webkit-align-items: center;
        -moz-box-align: center;
        align-items: center;
        cursor: pointer;
        z-index: 1;
        outline: none;
    }

    button.rotation img {
        width: 17px;
    }
</style>
