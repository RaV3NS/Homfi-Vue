<template>
    <div>
        <div class="expand">
            <button class="filterToggle" @click="open = !open">{{ label }}</button>
        </div>
        <VueSlideToggle :open="open" tag="section" :duration="500">
            <div class="v-filter-block">
                <div class="v-filter-block">
                    <div class="range-field">
                        <input
                            class="range-field-input"
                            type="number"
                            placeholder="от"
                            pattern="[0-9]*"
                            v-model="from"
                            v-on:blur="validateInput"
                            v-on:change="validateInput"
                        />

                        <input
                            class="range-field-input"
                            type="number"
                            placeholder="до"
                            pattern="[0-9]*"
                            v-model="to"
                            v-on:blur="validateInput"
                            v-on:change="validateInput"
                        />
                    </div>
                </div>
            </div>
        </VueSlideToggle>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                open: false
            }
        },
        mounted() {
            this.$nextTick(() => {
                window.addEventListener('resize', this.onResize);
            })
        },
        props: ["text", "store_prop"],
        computed: {
            from: {
                get() {
                    return this.$store.state[this.store_prop].from;
                },
                set(value) {
                    this.$store.dispatch("setFilter", { prop: this.store_prop, field: 'from', value: value })
                }
            },
            to: {
                get() {
                    return this.$store.state[this.store_prop].to;
                },
                set(value) {
                    this.$store.dispatch("setFilter", { prop: this.store_prop, field: 'to', value: value })
                }
            },
            label: function() {
                if (!this.from && !this.to)
                    return this.text;

                if (!this.from && this.to > 0) {
                    return "до " + this.to + " грн"
                }

                if (!this.to && this.from > 0) {
                    return "от " + this.from + " грн"
                }

                if (this.from > 0 && this.to > 0) {
                    return "от " + this.from + " до " + this.to + " грн"
                }
            }
        },
        methods: {
            handleRefresh() {
                this.label = this.text;
                this.to = "";
                this.from = "";
            },
            validateInput() {
                // #TODO Filter Logic
            },
            updateFilters() {
                this.$emit("filterChange", { prop: this.store_prop, data: { from: this.from, to: this.to } });
            },
            onResize() {
                this.windowWidth = window.innerWidth;
            },
            openModal() {
                this.$modal.show(this.store_prop + '-modal');
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../../sass/header.scss';
    @import '../../../../sass/base.scss';

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

    .expand:first-of-type {
        border-top: 1px solid var(--gray-300);
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

    .v-filter-block {
        padding: 0.5rem 0;
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

    .range-field {
        --rf-color: var(--blue-900);
        --rf-placeholder-color: var(--gray-600);
        --rf-border-color: var(--gray-300);
        --tf-border-color-focus: var(--blue-900);
        --tf-transition: border-color .2s;
        --rf-border: 1px solid var(--rf-border-color);
        --rf-border-radius: .375rem;
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
    }

    .range-field-input {
        -webkit-writing-mode: horizontal-tb !important;
        text-rendering: auto;
        color: -internal-light-dark(black, white);
        letter-spacing: normal;
        word-spacing: normal;
        text-transform: none;
        text-indent: 0px;
        text-shadow: none;
        display: inline-block;
        text-align: start;
        appearance: textfield;
        background-color: -internal-light-dark(rgb(255, 255, 255), rgb(59, 59, 59));
        -webkit-rtl-ordering: logical;
        cursor: text;
        margin: 0em;
        font: 400 13.3333px Arial;
        padding: 1px 2px;
        border-width: 2px;
        border-style: inset;
        border-color: -internal-light-dark(rgb(118, 118, 118), rgb(195, 195, 195));
        border-image: initial;

        width: 50%;
        outline: none;
        background-color: transparent;
        border: var(--rf-border);
        padding: .5rem .625rem;
        font-size: 16px;
        line-height: 22px;
        color: var(--rf-color);
        -webkit-transition: var(--tf-transition);
        transition: var(--tf-transition);
    }

    .range-field-input:first-child {
        border-radius: var(--rf-border-radius) 0 0 var(--rf-border-radius);
    }

    .range-field-input:last-child {
        border-left-width: 0;
        border-radius: 0 var(--rf-border-radius) var(--rf-border-radius) 0;
    }
</style>
