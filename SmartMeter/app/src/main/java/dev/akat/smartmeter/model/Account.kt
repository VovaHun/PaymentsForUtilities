package dev.akat.smartmeter.model

import com.google.gson.annotations.SerializedName
import java.util.*

data class Account(
    @field:SerializedName("id")
    val id: Long,
    @field:SerializedName("name")
    val name: String,
    @field:SerializedName("start_date")
    val startDate: Date,
    @field:SerializedName("end_date")
    val endDate: Date,
    @field:SerializedName("is_using")
    val isUsing: Boolean
)