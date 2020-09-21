<template>
    <div>
        <v-select
            @search="onSearch"
            label="name"
            :filterable="false"
            :options="options"
            v-model="selected"
            id="cityFilter"
            :placeholder="this.placeholder"
        >

            <template slot="no-options">
                Начните вводить название...
            </template>
            <template slot="option" slot-scope="option">
                <div class="d-center">
                    {{ option.name_ru }}
                </div>
            </template>
            <template slot="selected-option" slot-scope="option">
                <img class="autocomplete autocomplete__search-icon" src="/icons/search.svg" decoding="async" alt="Search Icon">
                <div class="selected d-center">
                    {{ option.name_ru }}
                </div>
            </template>
        </v-select>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            options: [],
            selected: null
        }
    },
    mounted() {
      if (this._selected !== undefined)
          this.selected = this._selected;
    },
    props: ["fetch_url", "_selected", "placeholder"],
    methods: {
        onSearch(search, loading) {
            loading(true);
            this.search(loading, search, this);
        },
        search: _.debounce((loading, search, vm) => {
            if (search !== '' && search !== undefined) {
                axios.get(vm.fetch_url + search)
                    .then((response) => {
                        vm.options = response.data;
                        loading(false);
                    })
            } else {
                loading(false);
            }

        }, 350)
    },
    watch: {
        selected: function (newValue, oldValue) {
            console.log(newValue.id);
        }
    }
}
</script>

<style>
    #cityFilter {
        margin-top: 1rem;
    }

    .autocomplete__search-icon {
        position: relative;
        top: -2px;
        margin-right: 0.5rem;
    }

    .vs__dropdown-toggle {
        padding: 0.3rem;
    }
</style>
