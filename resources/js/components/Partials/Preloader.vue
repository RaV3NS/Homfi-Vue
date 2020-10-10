<template>
    <svg
        class="spinner"
        width="24"
        height="24"
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
    >
        <path
            d="M22.8 12C22.1373 12 21.6079 12.54 21.5251 13.1975C20.9311 17.9178 16.8747 21.6 12 21.6C6.72 21.6 2.4 17.28 2.4 12C2.4 7.12534 6.08218 3.06894 10.8025 2.47486C11.46 2.3921 12 1.86274 12 1.2C12 0.537258 11.4612 -0.00634121 10.8018 0.0598072C4.75877 0.665985 0 5.80424 0 12C0 18.6 5.4 24 12 24C18.1958 24 23.334 19.2412 23.9402 13.1982C24.0063 12.5388 23.4627 12 22.8 12Z"
            fill="#3756C2"
        />
        <path
            d="M4.80005 11.9998C4.80005 15.553 7.40858 18.5266 10.8052 19.1C11.4587 19.2103 12 18.6625 12 17.9998C12 17.3371 11.4543 16.8151 10.8125 16.6502C8.74192 16.1182 7.20005 14.2303 7.20005 11.9998C7.20005 9.35981 9.36005 7.19981 12 7.19981C14.2305 7.19981 16.1184 8.74168 16.6504 10.8122C16.8153 11.4541 17.3373 11.9998 18 11.9998C18.6628 11.9998 19.2105 11.4585 19.1002 10.805C18.5268 7.40834 15.5533 4.7998 12 4.7998C8.04005 4.7998 4.80005 8.03981 4.80005 11.9998Z"
            fill="#3756C2"
        />
    </svg>
</template>

<script>
    export default {
        data: function() {
            return {

            }
        }
    }
</script>

<style lang="scss" scoped>
    .spinner {
        flex-basis: 24px;
        flex-grow: 0;
        flex-shrink: 0;

        path {
            transform-origin: center;

            &:first-child {
                animation: spin 1s linear infinite;
            }

            &:last-child {
                animation: spin 1.5s linear infinite;
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    }

</style>
