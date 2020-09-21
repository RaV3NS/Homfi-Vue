<template>
    <b-dropdown id="dropdown-text" :text="label" class="m-2 dropdown-filter">
        <b-dropdown-text>
            <CheckboxFilter
                v-for="(check, index) in store_prop"
                :text="check.text"
                :key="check.text"
                :id="index"
                :model="check.checked"
                :store_prop="store_p"
                :store_action="store_a"
                v-on:checked="handleChecked"
            ></CheckboxFilter>

            <div class="control-block">
                <a href="#" class="link link-info" v-on:click.stop.prevent="handleRefresh">Сбросить</a>
                <a href="#" class="btn btn-primary">Продолжить</a>
            </div>
        </b-dropdown-text>
    </b-dropdown>
</template>

<script>
    import CheckboxFilter from "../Forms/CheckboxFilter";
    import { mapState } from "vuex";

    export default {
        components: { CheckboxFilter },
        data: function () {
            return {
                selected: [],
                label: null
            }
        },
        props: ["text", "store_p", "store_a"],
        mounted() {
            this.label = this.text;
            this.store_prop.map((el, index) => {
                if (el.checked)
                    this.selected.push(el.text);
            });
        },
        computed: {
            ...mapState({
                store_prop (state) {
                    return state[this.store_p];
                }
            })
        },
        methods: {
            handleChecked (payload) {
                if (payload.value === true)
                    this.selected.push(payload.text);
                else
                    this.selected = this.selected.filter(item => item !== payload.text);
            },
            handleRefresh() {
                this.store_prop.map((el, index) => {
                   this.$store.dispatch(this.store_a, {id: index, value: false})
                   return el;
                });
                this.selected = [];
            }
        },
        watch: {
            selected: function(newVal, oldVal) {
                if (newVal.length > 0) {
                    this.label = newVal.join(', ');
                } else this.label = this.text;
            },
            label: function(newVal, oldVal) {
                if (newVal.length > 13)
                    this.label = newVal.substr(0, 13) + '...';
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../../sass/header.scss';
    @import '../../../../sass/base.scss';

    .dropdown-filter {
        text-align: center;
        width: fit-content;
    }

    .control-block {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 1rem;
    }

    .control-block .link {
        margin-right: 10px;
    }
</style>
