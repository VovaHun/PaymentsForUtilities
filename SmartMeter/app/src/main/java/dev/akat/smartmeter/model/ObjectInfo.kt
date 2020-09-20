package dev.akat.smartmeter.model

import com.google.gson.annotations.SerializedName

data class ObjectInfo(
    @field:SerializedName("id")
    val id: Long,
    @field:SerializedName("name")
    val name: String,
    @field:SerializedName("kadastr_no")
    val kadastr: String,
    @field:SerializedName("address")
    val address: String
)