<template>
    <v-row>
        <v-col lg="4" md="4" sm="4" xs="4">
            <v-select outlined :items="address_types" v-model="address_type" label="Address Type" dense></v-select>
        </v-col>
        <v-col lg="4" md="4" sm="4" xs="4">
            <v-text-field :error-messages="customErrors('no')" outlined v-model="no" label="House or Bldg. No" dense></v-text-field>
        </v-col>
        <v-col lg="4" md="4" sm="4" xs="4">
            <v-text-field :error-messages="customErrors('street')" outlined v-model="street" label="Street" dense></v-text-field>
        </v-col>
        <v-col lg="4" md="4" sm="4" xs="4">
            <v-text-field :error-messages="customErrors('city')" outlined v-model="city" label="City" dense></v-text-field>
        </v-col>
        <v-col lg="4" md="4" sm="4" xs="4">
            <v-text-field :error-messages="customErrors('state')" outlined v-model="state" label="State" dense></v-text-field>
        </v-col>
        <v-col lg="4" md="4" sm="4" xs="4">
            <v-select outlined :items="countries" v-model="country" label="Country" dense></v-select>
        </v-col>
        <v-col lg="4" md="4" sm="4" xs="4">
            <v-text-field :error-messages="customErrors('post_code')" outlined v-model="post_code" label="Post Code" dense></v-text-field>
        </v-col>
    </v-row>
</template>

<script>
import { required, numeric } from "vuelidate/lib/validators"
import { validationMixin } from "vuelidate"
export default {
    mixins: [validationMixin],
    props: ["title"],
    data() {
        return {
            address_type: 1,
            address_types: [],
            countries: [
                "Afghanistan",
                "Åland Islands",
                "Albania",
                "Algeria",
                "American Samoa",
                "Andorra",
                "Angola",
                "Anguilla",
                "Antarctica",
                "Antigua And Barbuda",
                "Argentina",
                "Armenia",
                "Aruba",
                "Australia",
                "Austria",
                "Azerbaijan",
                "Bahamas",
                "Bahrain",
                "Bangladesh",
                "Barbados",
                "Belarus",
                "Belgium",
                "Belize",
                "Benin",
                "Bermuda",
                "Bhutan",
                "Bolivia, Plurinational State Of",
                "Bonaire, Sint Eustatius And Saba",
                "Bosnia And Herzegovina",
                "Botswana",
                "Bouvet Island",
                "Brazil",
                "British Indian Ocean Territory",
                "Brunei Darussalam",
                "Bulgaria",
                "Burkina Faso",
                "Burundi",
                "Cambodia",
                "Cameroon",
                "Canada",
                "Cape Verde",
                "Cayman Islands",
                "Central African Republic",
                "Chad",
                "Chile",
                "China",
                "Christmas Island",
                "Cocos (keeling) Islands",
                "Colombia",
                "Comoros",
                "Congo",
                "Congo, The Democratic Republic Of The",
                "Cook Islands",
                "Costa Rica",
                "Côte D'ivoire",
                "Croatia",
                "Cuba",
                "Curaçao",
                "Cyprus",
                "Czech Republic",
                "Denmark",
                "Djibouti",
                "Dominica",
                "Dominican Republic",
                "Ecuador",
                "Egypt",
                "El Salvador",
                "Equatorial Guinea",
                "Eritrea",
                "Estonia",
                "Ethiopia",
                "Falkland Islands (malvinas)",
                "Faroe Islands",
                "Fiji",
                "Finland",
                "France",
                "French Guiana",
                "French Polynesia",
                "French Southern Territories",
                "Gabon",
                "Gambia",
                "Georgia",
                "Germany",
                "Ghana",
                "Gibraltar",
                "Greece",
                "Greenland",
                "Grenada",
                "Guadeloupe",
                "Guam",
                "Guatemala",
                "Guernsey",
                "Guinea",
                "Guinea-bissau",
                "Guyana",
                "Haiti",
                "Heard Island And Mcdonald Islands",
                "Holy See (vatican City State)",
                "Honduras",
                "Hong Kong",
                "Hungary",
                "Iceland",
                "India",
                "Indonesia",
                "Iran, Islamic Republic Of",
                "Iraq",
                "Ireland",
                "Isle Of Man",
                "Israel",
                "Italy",
                "Jamaica",
                "Japan",
                "Jersey",
                "Jordan",
                "Kazakhstan",
                "Kenya",
                "Kiribati",
                "Korea, Democratic People's Republic Of",
                "Korea, Republic Of",
                "Kuwait",
                "Kyrgyzstan",
                "Lao People's Democratic Republic",
                "Latvia",
                "Lebanon",
                "Lesotho",
                "Liberia",
                "Libya",
                "Liechtenstein",
                "Lithuania",
                "Luxembourg",
                "Macao",
                "Macedonia, The Former Yugoslav Republic Of",
                "Madagascar",
                "Malawi",
                "Malaysia",
                "Maldives",
                "Mali",
                "Malta",
                "Marshall Islands",
                "Martinique",
                "Mauritania",
                "Mauritius",
                "Mayotte",
                "Mexico",
                "Micronesia, Federated States Of",
                "Moldova, Republic Of",
                "Monaco",
                "Mongolia",
                "Montenegro",
                "Montserrat",
                "Morocco",
                "Mozambique",
                "Myanmar",
                "Namibia",
                "Nauru",
                "Nepal",
                "Netherlands",
                "New Caledonia",
                "New Zealand",
                "Nicaragua",
                "Niger",
                "Nigeria",
                "Niue",
                "Norfolk Island",
                "Northern Mariana Islands",
                "Norway",
                "Oman",
                "Pakistan",
                "Palau",
                "Palestine, State Of",
                "Panama",
                "Papua New Guinea",
                "Paraguay",
                "Peru",
                "Philippines",
                "Pitcairn",
                "Poland",
                "Portugal",
                "Puerto Rico",
                "Qatar",
                "Réunion",
                "Romania",
                "Russian Federation",
                "Rwanda",
                "Saint Barthélemy",
                "Saint Helena, Ascension And Tristan Da Cunha",
                "Saint Kitts And Nevis",
                "Saint Lucia",
                "Saint Martin (french Part)",
                "Saint Pierre And Miquelon",
                "Saint Vincent And The Grenadines",
                "Samoa",
                "San Marino",
                "Sao Tome And Principe",
                "Saudi Arabia",
                "Senegal",
                "Serbia",
                "Seychelles",
                "Sierra Leone",
                "Singapore",
                "Sint Maarten (dutch Part)",
                "Slovakia",
                "Slovenia",
                "Solomon Islands",
                "Somalia",
                "South Africa",
                "South Georgia And The South Sandwich Islands",
                "South Sudan",
                "Spain",
                "Sri Lanka",
                "Sudan",
                "Suriname",
                "Svalbard And Jan Mayen",
                "Swaziland",
                "Sweden",
                "Switzerland",
                "Syrian Arab Republic",
                "Taiwan, Province Of China",
                "Tajikistan",
                "Tanzania, United Republic Of",
                "Thailand",
                "Timor-leste",
                "Togo",
                "Tokelau",
                "Tonga",
                "Trinidad And Tobago",
                "Tunisia",
                "Turkey",
                "Turkmenistan",
                "Turks And Caicos Islands",
                "Tuvalu",
                "Uganda",
                "Ukraine",
                "United Arab Emirates",
                "United Kingdom",
                "United States",
                "United States Minor Outlying Islands",
                "Uruguay",
                "Uzbekistan",
                "Vanuatu",
                "Venezuela, Bolivarian Republic Of",
                "Viet Nam",
                "Virgin Islands, British",
                "Virgin Islands, U.s.",
                "Wallis And Futuna",
                "Western Sahara",
                "Yemen",
                "Zambia",
                "Zimbabwe"
            ],
            no: "",
            street: "",
            city: "",
            state: "",
            country: "Philippines",
            post_code: "",
            valid: false
        }
    },
    validations: {
        no: {
            required
        },
        street: {
            required
        },
        city: {
            required
        },
        state: {
            required
        },
        country: {
            required
        },
        post_code: {
            required
        }
    },
    created() {
        this.getAddressTypes()
    },
    methods: {
        customErrors(property_name) {
            var errors = []
            if (!this.$v[property_name].$dirty) return (errors = [])
            !this.$v[property_name].required && errors.push(`${property_name} field is required`)
            return errors
        },
        getAddressTypes: function() {
            axios.get("/admin/address/get-address-type").then(result => {
                this.address_types = result.data.address_types.map(type => {
                    return {
                        text: type.address_type_name,
                        value: type.id
                    }
                })
            })
        },
        getValues: function() {
            this.$v.$touch()
            var addresses = {
                address_type: this.address_type,
                no: this.no,
                street: this.street,
                city: this.city,
                state: this.state,
                country: this.country,
                post_code: this.post_code
            }
            if (!this.$v.$invalid) {
                this.valid = true
                this.$emit("getValues", addresses)
            } else {
                this.valid = false
            }
        },
        reset: function() {
            this.no = ""
            this.street = ""
            this.city = ""
            this.state = ""
            this.country = ""
            this.post_code = ""
            this.valid = false
            this.$v.$reset()
        }
    }
}
</script>

<style></style>
