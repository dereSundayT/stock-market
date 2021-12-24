<template>
      <v-row justify="center">
                        <v-dialog
                            v-model="dialog"
                            persistent
                            max-width="600px"
                        >
                            <v-card>
                                <v-card-title>
                                    <span class="text-h5">Update Stock Unit Price</span>
                                    <v-spacer></v-spacer>
                                </v-card-title>
                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12">
                                                <v-text-field
                                                label="Stock unit Price*"
                                                type="number"
                                                v-model="stock.unit_price"
                                                required
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="dialog=false"
                                >
                                    Close
                                </v-btn>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="updateUnitPrice"
                                >
                                    Update
                                </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-row>
</template>


<script>
export default {
     props: ['stock','dialog','updateUnitPrice'],
}
</script>