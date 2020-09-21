<template>
    <div class="item">
        <div class="subitem">
            <span class="more-filters-title">{{ label }} <span v-if="unit">, {{ unit }}</span></span>
            <div class="more-filters-field">
                <input type="text" placeholder="от" class="more-filters-input-half first" v-model="from" v-on:blur="validate" />
                <input type="text" placeholder="до" class="more-filters-input-half second" v-model="to" v-on:blur="validate" />
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";

export default {
    data: function() {
        return {
            //
        }
    },
    mounted() {
        console.log(this.store_prop.from);
    },
    watch: {
        from: function(newVal, oldVal) {
            if (newVal)
                this.from = newVal.replace(/[^0-9]/g,'');
        },
        to: function(newVal, oldVal) {
            if (newVal)
                this.to = newVal.replace(/[^0-9]/g,'');
        },
    },
    computed: {
        ...mapState({
            store_prop (state) {
                return state['alt_filters'][this.name];
            }
        }),
        from: {
            get() {
                return this.store_prop.from;
            },
            set(value) {
                this.$store.dispatch("setAltFilter", { prop: this.name, field: 'from', value: value });
            }
        },
        to: {
            get() {
                return this.store_prop.to;
            },
            set(value) {
                this.$store.dispatch("setAltFilter", { prop: this.name, field: 'to', value: value });
            }
        }
    },
    props: ["label", "name", "unit"],
    methods: {
        validate() {
            if (this.to && parseFloat(this.from) > parseFloat(this.to)) {
                let from = this.to;
                this.to = this.from;
                this.from = from;
            }
        }
    }
}
</script>

<style lang="scss" scoped>
    .more-filters-title {
        color: var(--blue-900);
        font-size: 16px;
        line-height: 22px;
        font-weight: 500;
    }

    .subitem {
        grid-row-start: 1;
        grid-column: 1/span 3;
    }

    input.first {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-right: none;
    }

    input.second {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    input {
        margin: 0;
        -webkit-flex-basis: 50%;
        flex-basis: 50%;
        max-width: 50%;
        border: 1px solid #d5dbe5;
        width: 100%;
        border-radius: 6px;
        padding: 10px 16px;
        font-size: 16px;
        line-height: 1.4;
        color: var(--greyish-blue);
        background-color: var(--white);
    }

    .more-filters-field {
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        position: relative;
    }
</style>
