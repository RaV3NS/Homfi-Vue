<template>
    <label class="check">
        <input
            class="check__input"
            type="checkbox"
            v-model="model"
        />
        <span class="check__box" />
        <span class="check__text">{{ text }}</span>
    </label>
</template>

<script>
    export default {
        data: function() {
            return {
                //
            }
        },
        computed: {
            model: {
                get() {
                    return this.$store.state.alt_filters[this.name].value;
                },
                set(value) {
                    this.$store.dispatch("setAltFilter", {prop: this.name, field: "value", value: value})
                }
            }
        },
        props: ["text", "name"]
    }
</script>

<style lang="scss" scoped>
    .check {
        --check-text-transition: color .2s;
        --check-box-transition: background-color .2s, border .2s;

        --check-text-will-change: color;
        --check-box-will-change: background-color, border;

        --check-box-border: 1px solid var(--gray-300);

        $check: &;

        width: 100%;
        display: inline-flex;
        margin: 0.3rem 0;

        display: inline-flex;
        align-items: center;

        &:hover {
            --check-box-border: 1px solid var(--blue-900);

            & > #{$check}__text {
                color: var(--blue-900);
            }
        }

        &__input {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            clip: rect(0 0 0 0);

            &:checked + #{$check}__box {
                --check-box-border: 1px solid var(--blue-900);

                background-color: var(--blue-900);
                background-image: url("/icons/check.svg");
            }

            &:checked ~ #{$check}__text {
                color: var(--blue-900);
                font-weight: 500;
            }

            &:focus + #{$check}__box {
                --check-box-border: 1px solid var(--blue-900);
            }
        }

        &__box {
            margin-right: .625em;
            width: 1.25rem;
            height: 1.25rem;
            border-radius: .25rem;
            border: var(--check-box-border);

            background-size: 12px 8px;
            background-position: center;
            background-repeat: no-repeat;

            transition: var(--check-box-transition);
            will-change: var(--check-box-will-change);

            &:hover {
                cursor: pointer;
            }
        }

        &__text {
            font-size: 16px;
            line-height: 22px;
            font-weight: 300;

            color: var(--greyish-blue);

            transition: var(--check-text-transition);
            will-change: var(--check-text-will-change);
        }
    }
</style>
