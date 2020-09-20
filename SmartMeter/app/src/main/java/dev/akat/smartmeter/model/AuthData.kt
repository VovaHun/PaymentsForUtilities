package dev.akat.smartmeter.model

import com.google.gson.annotations.SerializedName

data class AuthData(
    @field:SerializedName("id")
    val id: Long
)