package dev.akat.smartmeter.model

import com.google.gson.annotations.SerializedName

data class Unit(
    @field:SerializedName("id")
    val id: Long,
    @field:SerializedName("name")
    val name: String
)