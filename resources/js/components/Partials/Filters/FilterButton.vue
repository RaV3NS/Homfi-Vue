<template>
    <div>
        <div v-if="windowWidth > 600">
            <v-menu
                v-model="menu"
                offset-y
                transition="scale-transition"
                :close-on-content-click="false"
            >
            <template v-slot:activator="{ on }">
                <button
                    v-on="on"
                    class="btn btn-filter v-btn-filter"
                >
                    {{ label }}
                </button>
            </template>

            <v-card width="300px">
                <div class="v-filter-block">
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
                        <a href="#" class="btn btn-primary" v-on:click.stop.prevent="setFilters">Продолжить</a>
                    </div>
                </div>
            </v-card>
        </v-menu>
        </div>

        <div class="dropdown-filter" v-else>
            <button class="btn dropdown-toggle dropdown-filter" style="font-size: 13px" @click="openModal">{{ label }}</button>

            <modal :name="store_p + '-modal'" height="auto" :adaptive="true">
                <div class="modal-filter-header">
                    <h2>{{ text }}</h2>
                    <img src="/icons/close.svg" alt="close" class="close_modal_btn" @click="$modal.hide(store_p + '-modal')">
                </div>

                <div class="wrapper">
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

                    <div class="filter-toolbar">
                        <a href="#" class="link link-info" v-on:click.stop.prevent="handleRefresh">Сбросить</a>
                        <a href="#" class="btn btn-primary" v-on:click.stop.prevent="setFilters">Продолжить</a>
                    </div>
                </div>
            </modal>
        </div>
    </div>
</template>

<script>
    import CheckboxFilter from "../Forms/CheckboxFilter";
    import { mapState } from "vuex";

    export default {
        components: { CheckboxFilter },
        data: function () {
            return {
                selected: [],
                label: null,
                windowWidth: window.innerWidth,
                menu: false,
            }
        },
        props: ["text", "store_p", "store_a"],
        mounted() {
            this.label = this.text;
            this.store_prop.map((el, index) => {
                if (el.checked)
                    this.selected.push(el.text);
            });

            this.$nextTick(() => {
                window.addEventListener('resize', this.onResize);
            })
        },
        computed: {
            ...mapState({
                store_prop (state) {
                    return state[this.store_p];
                }
            })
        },
        methods: {
            setFilters() {
                this.$emit("setFilters", 1);
            },
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
            },
            onResize() {
                this.windowWidth = window.innerWidth;
            },
            openModal() {
                this.$modal.show(this.store_p + '-modal');
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

    .v-btn-filter {
        border-color: var(--gray-300) !important;
        --border-radius: 6px;
        display: flex;
        box-sizing: border-box;
        border: 1px solid var(--gray-300);
        background-color: var(--white) !important;
        text-align: left;
        height: 45px;
        color: var(--greyish-blue);
        width: -webkit-fit-content;
        width: -moz-fit-content;
        width: fit-content;
        padding: 0.6rem 1.2rem;
        justify-content: center;
        margin: 0.5rem 0;
        min-width: 144px;
        margin-left: 0.5rem;
    }

    .v-filter-block {
        padding: 1rem;
        z-index: 12000;
    }

    .filter-toolbar {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: .625rem 1.25rem;
        background-color: var(--white);
        box-shadow: 0 0 1.25rem rgba(100,110,148,.2);
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -webkit-justify-content: space-between;
        -moz-box-pack: justify;
        justify-content: space-between;
        -webkit-align-items: center;
        -moz-box-align: center;
        align-items: center;
    }

    .filter-toolbar .link-info {
        text-decoration: none;
    }

    .modal-filter-header {
        display: flex;
        align-items: center;
    }

    .modal-filter-header .close_modal_btn {
        padding: 0 1rem !important;
    }

    .modal-filter-header h2 {
        padding: 1rem 2rem;
        line-height: 24px;
        color: var(--text-color-primary);
    }

    .dropdown-filter {
        text-align: center;
        width: fit-content;
        font-size: 18px;
        font-weight: 400;
        margin-bottom: 10px;
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
