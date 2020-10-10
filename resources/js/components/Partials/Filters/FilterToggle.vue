<template>
    <div>
        <div>
            <div class="expand">
                <button class="filterToggle" @click="open = !open">{{ label }}</button>
            </div>
            <VueSlideToggle :open="open" tag="section" :duration="500">
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
                </div>
            </VueSlideToggle>
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
                open: false
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
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../../sass/header.scss';
    @import '../../../../sass/base.scss';

    .v-filter-block {
        padding: 1rem 0;
    }

    .expand:first-of-type {
        border-top: 1px solid var(--gray-300);
    }

    .expand {
        color: var(--text-color-primary);
        border-bottom: 1px solid var(--gray-300);
    }

    .filterToggle {
        width: 100%;
        border: none;
        background: none;
        outline: none;
        font-size: 16px;
        color: currentColor;
        position: relative;
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -webkit-align-items: center;
        -moz-box-align: center;
        align-items: center;
        -webkit-justify-content: space-between;
        -moz-box-pack: justify;
        justify-content: space-between;
        padding: .75rem .675rem .75rem 0;
    }

    .filterToggle::after {
        content: "";
        display: block;
        width: .45rem;
        height: .45rem;
        border-top: 2px solid;
        border-right: 2px solid;
        -webkit-transform: rotate(45deg);
        transform: rotate(45deg);
    }

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
